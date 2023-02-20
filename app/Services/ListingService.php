<?php

namespace App\Services;

use App\Models\Listing;

class ListingService {

    public function all()
    {
        $listings = Listing::with('company')->get();
        return $listings;
    }

}