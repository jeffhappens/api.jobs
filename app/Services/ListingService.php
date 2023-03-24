<?php

namespace App\Services;

use App\Models\Listing;

class ListingService {

    public function all()
    {
        $listings = Listing::with('company')
            ->with('industry')
            ->where( 'expires_at','>', now() )
            ->latest()
            ->paginate(10);
        return $listings;
    }

    public function show($uuid, $slug)
    {
        $listing = Listing::with('company')
            ->with('industry')
            ->where([
            'uuid' => $uuid,
            'slug' => $slug
        ])->first();

        return $listing;

    }

}