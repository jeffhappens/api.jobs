<?php

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Http\Request;
use App\Models\TemporaryFolder;
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
