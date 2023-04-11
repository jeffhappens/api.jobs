<?php

use App\Models\State;
use App\Models\Company;
use App\Models\Listing;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\TemporaryFolder;
use App\Models\TemporaryListing;
use App\Services\CompanyService;
use App\Services\ListingService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ListingRequest;
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

    Route::get('/states', function() {
        return response()->json(State::get());
    });

    Route::get('/industries', function() {
        return response()->json(Industry::get());
    });

    Route::get('/companies/{uuid}', function($uuid) {
        $companies = Company::withCount('listings')
            ->with('industry')
            ->where('author', $uuid)
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

    Route::get('/mylistings', function(Request $request) {
        $listings = Listing::with('industry')
            ->with('company')
            ->where('author_uuid', $request->user()->uuid)
            ->latest()
            ->get();
            
        return $listings;
    });
    
    
    
    
    
    Route::post('/listing/add', function(Request $request, ListingService $listingService)
    {
        $listing_fields = ['uuid', 'title','type_id', 'apply_link', 'description', 'author_uuid', 'company_id', 'industry_id'];
        $listing = $listingService->add( $request->only($listing_fields) );

        return $listing;
    });
    
    
    
    
    
    Route::post('/addtemplisting', function(Request $request) {

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

    Route::post('/listing/validate', function(ListingRequest $request) {
        return;
    });


});



Route::get('/company/{uuid}/{slug}', [CompanyController::class, 'single']);




Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/industries', [IndustryController::class, 'index']);

Route::get('/industries/{slug}', [IndustryController::class, 'listings']);



Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listing/edit/{uuid}', [ListingController::class, 'edit']);

Route::get('/listing/{uuid}/{slug}', [ListingController::class, 'show']);

// Route::post('/search', [SearchController::class, 'index']);



Route::post('/search', function(Request $request) {

    $listings = Listing::with('company')
        ->where('title','like','%'.$request->get('keyword').'%')
        ->paginate(25);

    return response()->json( $listings );

});



Route::post('/webhook/stripe', function(Request $request) {

    \Stripe\Stripe::setApiKey( config('services.stripe.test_secret') );
    // Replace this endpoint secret with your endpoint's unique secret
    // If you are testing with the CLI, find the secret by running 'stripe listen'
    // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
    // at https://dashboard.stripe.com/webhooks

    $endpoint_secret = config('services.stripe.webhook_secret');

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

            $uuid = $checkoutSession->client_reference_id;
            $field = 'author_uuid';

            // $uuid is prefixed if the action is renew
            if(Str::contains($uuid, 'renewal_')) {
                $field = 'uuid';
                $uuid = Str::remove('renewal_', $uuid);
            }

            $listing = Listing::where($field, $uuid)->latest()->first();
                $listing->expires_at = Carbon::now()->addMonth();
                if($field === 'uuid') {
                    $listing->renewed_on = Carbon::now();
                }
                $listing->save();

            Log::info($listing);
            break;
        default:
        // Unexpected event type
        Log::info('Received unknown event type');
    }
    http_response_code(200);

});
