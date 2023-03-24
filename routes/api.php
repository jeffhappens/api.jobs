<?php

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\TemporaryFolder;
use App\Models\TemporaryListing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\Auth\NewPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', [ UserController::class, 'authenticatedUser' ]);
    
    Route::post('/change-password', [ NewPasswordController::class, 'fromAccountPanel' ]);

    Route::get('/companies/edit/{id}', function($id) {

        $company = Company::where('id', $id)->first();
        return $company;

    });

    Route::get('/companies/{uuid}', function($uuid) {
        $companies = Company::withCount('listings')
            ->with('industry')
            ->where('user_id', $uuid)
            ->latest()
            ->get();
        return $companies;
    });

    Route::post('/company/add', [CompanyController::class, 'store']);
    Route::post('/company/update', [CompanyController::class, 'update']);

    Route::post('/company/logo/add', [CompanyController::class, 'logo']);

    Route::get('logo/{folderId}', function($folderId) {

        $file = TemporaryFolder::where('folder', $folderId)->first();

        return $file;

    });

    Route::post('/listing/add', function(Request $request) {

        $listing = new Listing;
        $listing->uuid = Str::uuid();
        $listing->author_uuid = $request->user()->uuid;
        $listing->title = $request->get('title');
        $listing->slug = Str::slug($request->get('title'));
        $listing->company_id = $request->get('company')['id'];
        $listing->industry_id = $request->get('industry')['id'];
        $listing->description = $request->get('description');
        $listing->apply_link = $request->get('apply_link');
        // $listing->expires_at = Carbon::now()->addMonth();
        $listing->save();

        return $listing;

    });


    Route::post('/addtemplisting', function(Request $request) {

        // return response()->json( $request->all() );

        $tempListing = TemporaryListing::updateOrCreate(
            [ 'user_uuid' => $request->get('user_uuid') ],
            [
                'title' => $request->get('title'),
                'industry_id' => $request->get('industry')['id'],
                'company_id' => $request->get('company')['id'],
                'apply_link' => $request->get('apply_link'),
                'job_type_id' => 1,
                'description' => $request->get('description')
            ]
        );
        return $tempListing;

    });


    Route::post('/updatetemplisting', function(Request $request) {
        $tempListing = TemporaryListing::where('user_uuid', $request->get('uuid'))
            ->first();
        $tempListing->company_id = $request->get('company_id');
        $tempListing->save();
        return $tempListing;

    });

    Route::get('/templisting/{uuid}', function($uuid) {

        $tempListing = TemporaryListing::with('industry')
            ->with('company')
            ->where('user_uuid', $uuid)
            ->latest()
            ->first();
        return $tempListing;

    });


});



Route::get('/company/{uuid}/{slug}', [CompanyController::class, 'single']);




Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/industries', [IndustryController::class, 'index']);

Route::get('/industries/{slug}', [IndustryController::class, 'listings']);



Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listing/{uuid}/{slug}', [ListingController::class, 'show']);

Route::post('/search', [SearchController::class, 'index']);



Route::post('/search', function(Request $request) {

    $listings = Listing::with('company')
        ->where('title','like','%'.$request->get('keyword').'%')
        ->paginate(25);

    return response()->json( $listings );

});



Route::post('/webhook/stripe', function(Request $request) {

    \Stripe\Stripe::setApiKey('sk_test_FMOOV9UsEGqeeaXKrzteVlCA00x6B9HCU0');
    // Replace this endpoint secret with your endpoint's unique secret
    // If you are testing with the CLI, find the secret by running 'stripe listen'
    // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
    // at https://dashboard.stripe.com/webhooks

    $endpoint_secret = 'whsec_2bb82db99b4fdb182676697c294664c56ad230ab08722a7e52e831add78ee202';

    $payload = @file_get_contents('php://input');
    $event = null;

    try {
        $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
    } catch(\UnexpectedValueException $e) {
        // Invalid payload
        Log::info('Webhook error while parsing basic request.');
        abort(500);
        exit();
    }
    
    if ($endpoint_secret) {
        // Only verify the event if there is an endpoint secret defined
        // Otherwise use the basic decoded event
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::info('Webhook error while validating signature.');
            abort(500);
            // http_response_code(400);
            exit();
        }
    }

    // Handle the event
    switch ($event->type) {
        case 'checkout.session.completed':
            $checkoutSession = $event->data->object; // contains a \Stripe\PaymentIntent
            // Then define and call a method to handle the successful payment intent.
            // handlePaymentIntentSucceeded($paymentIntent);
            $author_uuid = $checkoutSession->client_reference_id;

            $listing = Listing::where('author_uuid', $author_uuid)->latest()->first();
            $listing->expires_at = Carbon::now()->addMonth();
            $listing->save();


            Log::info($listing);
            break;
        // case 'payment_intent.succeeded':
        //     $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
        //     // Then define and call a method to handle the successful payment intent.
        //     // handlePaymentIntentSucceeded($paymentIntent);
        //     Log::info($paymentIntent);
        //     break;
        // case 'payment_method.attached':
        //     $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
        //     // Then define and call a method to handle the successful attachment of a PaymentMethod.
        //     // handlePaymentMethodAttached($paymentMethod);
        //     Log::info($paymentMethod);
        //     break;
        default:
        // Unexpected event type
        Log::info('Received unknown event type');
    }
    http_response_code(200);

});
