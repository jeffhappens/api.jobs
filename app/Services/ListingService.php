<?php

namespace App\Services;

use App\Models\JobType;
use App\Models\Listing;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
            ->with('type')
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
                'type_id' => $data['type_id'],
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