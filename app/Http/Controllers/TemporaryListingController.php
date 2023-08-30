<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemporaryListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tempListing = TemporaryListing::updateOrCreate(

            ['user_uuid' => $request->get('user_uuid')],

            [
                'title' => $request->get('title'),
                'industry_id' => $request->get('industry')['id'],
                'company_id' => $request->get('company')['id'],
                'apply_link' => $request->get('apply_link'),
                'job_type_id' => 1,
                'description' => $request->get('description'),
            ]
        );

        return $tempListing;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tempListing = TemporaryListing::with('industry')
            ->with('company')
            ->where('user_uuid', $uuid)
            ->latest()
            ->first();

        return $tempListing;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tempListing = TemporaryListing::where('user_uuid', $request->get('uuid'))
            ->first();

        $tempListing->company_id = $request->get('company_id');
        $tempListing->save();

        return $tempListing;
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
}
