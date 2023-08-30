<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Services\ListingService;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListingService $listings)
    {
        return response()->json($listings->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, ListingService $listingService)
    {
        $listing_fields = ['uuid', 'title', 'type_id', 'apply_link', 'description', 'author_uuid', 'company_id', 'industry_id'];
        $listing = $listingService->add($request->only($listing_fields));

        return $listing;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show(ListingService $listingService, $uuid, $slug)
    {
        $listing = $listingService->show($uuid, $slug);

        return response()->json($listing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Listing::with('company')
            ->where('uuid', $id)
            ->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ListingService $listingService, Request $request)
    {
        $listing = $listingService->add($request->all());

        return $listing;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a list of a specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mylistings(ListingService $listingService, Request $request)
    {
        $listings = $listingService->mylistings($request->user()->uuid);

        return response()->json($listings);
    }
}
