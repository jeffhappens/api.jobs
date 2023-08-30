<?php

namespace App\Services;

use App\Models\Industry;

class IndustryService
{
    public function all()
    {
        $industries = Industry::withCount([
            'listings' => function ($query) {
                $query->where('expires_at', '>', now());
            }
        ])
        ->get();

        return $industries;
    }

    public function listings($slug)
    {
        $listings = Industry::with([
            'listings' => function($query) {
                $query->where('expires_at', '>', now());
            }
        ])
        ->where('slug', $slug)
        ->first();

        return $listings;
    }
}
