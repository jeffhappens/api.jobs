<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/listings', function() {

    $listings = Listing::with('company')->get();

    return response()->json( $listings );

});

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
