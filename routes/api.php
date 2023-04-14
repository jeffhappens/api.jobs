<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

use Stripe\Stripe;
use Stripe\Webhook;

use App\Models\State;
use App\Models\Company;
use App\Models\Listing;
use App\Models\Industry;
use App\Models\TemporaryFolder;
use App\Models\TemporaryListing;

use App\Http\Controllers\UserController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\TemporaryListingController;

use App\Services\CompanyService;
use App\Services\ListingService;

use App\Http\Requests\ListingRequest;

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
    
    Route::prefix('companies')->group(function() {
        Route::get('/edit/{id}', [ CompanyController::class, 'edit' ]);
        Route::get('/{uuid}', [ CompanyController::class, 'show' ]);
    });

    Route::prefix('company')->group(function() {

        Route::post('/add', [ CompanyController::class, 'store' ]);
        Route::post('/update', [ CompanyController::class, 'update' ]);    
        Route::post('/logo/add', [ CompanyController::class, 'logo' ]);    
        Route::get('/{uuid}/{slug}', [ CompanyController::class, 'single' ]);

    });

    Route::prefix('states')->group(function() {

        Route::get('/', [ StateController::class, 'index' ]);

    });







    Route::prefix('listing')->group(function() {

        Route::post('/add', [ ListingController::class, 'create' ]);
        Route::get('/edit/{uuid}', [ ListingController::class, 'edit' ]);
        Route::get('/{uuid}/{slug}', [ ListingController::class, 'show' ]);

    });

    Route::get('logo/{folderId}', function($folderId) {

        $file = TemporaryFolder::where('folder', $folderId)->first();
        return $file;

    });

    Route::get('/mylistings', [ ListingController::class, 'mylistings' ]);

    // Route::get('/mylistings', function(Request $request)
    // {
    //     $listings = Listing::with('industry')
    //         ->with('company')
    //         ->where('author_uuid', $request->user()->uuid)
    //         ->latest()
    //         ->get();

    //     return $listings;

    // });

    Route::post('/addtemplisting', [ TemporaryListingController::class, 'store' ]);
    Route::post('/updatetemplisting', [ TemporaryListingController::class, 'store' ]);
    Route::get('/templisting/{uuid}', [ TemporaryListingController::class, 'show' ]);

    Route::post('/listing/validate', function(ListingRequest $request) {
        return;
    });

});




// Unprotected Routes
Route::prefix('listings')->group(function() {
    Route::get('/', [ ListingController::class, 'index' ]);
});

Route::get('/companies', [ CompanyController::class, 'index' ]);

Route::prefix('industries')->group(function() {
    Route::get('/', [ IndustryController::class, 'index' ]);
    Route::get('/{slug}', [ IndustryController::class, 'listings' ]);
});

Route::post('/search', [ SearchController::class, 'index' ]);

Route::prefix('webhook')->group(function() {

    Route::post('/stripe', [ WebhookController::class, 'stripe' ]);

});
