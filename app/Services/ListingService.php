<?php

namespace App\Services;

use App\Models\Listing;

class ListingService {

    public function all()
    {
        $listings = Listing::with('company')->paginate(25);
        return $listings;
    }

    public function show($uuid, $slug)
    {
        $listing = Listing::where([
            'uuid' => $uuid,
            'slug' => $slug
        ])->first();

        return $listing;

    }

}