<?php

namespace App\Services;

use App\Models\Industry;

class IndustryService {

    public function all()
    {
        $industries = Industry::withCount('listings')->get();
        return $industries;
    }



    public function listings($slug)
    {

        $listings = Industry::with('listings.company.industry')
            ->where('slug', $slug)
            ->first();
        return $listings;

    }
}