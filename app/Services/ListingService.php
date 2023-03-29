<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Support\Str;

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
            ])
            ->first();

        return $listing;
    }
    
    
    
    
    
    public function add($data)
    {
        // return $data;

        $listing = Listing::updateOrCreate(
            [
                'uuid' => $data['uuid']
            ],
            [
                'uuid' => Str::uuid(),
                'author_uuid' => $data['author_uuid'],
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'description' => $data['description'],
                'apply_link' => $data['apply_link'],
                'company_id' => $data['company_id'],
                'industry_id' => $data['industry_id']
            ]
        );
        return $listing;

    }
}