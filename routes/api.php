<?php

use App\Models\Company;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/change-password', [NewPasswordController::class, 'fromAccountPanel']);

    Route::get('/companies/{uuid}', function($uuid) {
        $companies = Company::withCount('listings')
            ->with('industry')
            ->where('user_id', $uuid)
            ->get();
        return $companies;
    });

    Route::post('/company/add', [CompanyController::class, 'store']);
    Route::post('/company/logo/add', [CompanyController::class, 'logo']);


});




Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/industries', [IndustryController::class, 'index']);


Route::get('/listings', [ListingController::class, 'index']);

Route::post('/search', [SearchController::class, 'index']);











Route::post('/search', function(Request $request) {

    $latitude = '38.388320';
    $longitude = '-75.162190';

    if( $request->get('distance') === 'unlimited' ) {
        
        $listings = Listing::with('company')
        ->where('title','like','%'.$request->get('keyword').'%')
        ->get();

    } else {

        $radius = $request->get('distance');

        $selectWithinRadius = 'id, company_id, title, description, apply_link, ( 3956 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance';

        $listings = Listing::with('company')
        ->selectRaw( $selectWithinRadius, [ $latitude, $longitude, $latitude ] )
        ->having("distance", "<", $radius)
        ->where('title','like','%'.$request->get('keyword').'%')
        ->orderBy("distance",'asc')
        ->get();

    }
    return response()->json( $listings );

});
