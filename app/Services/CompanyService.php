<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Str;
use App\Models\TemporaryFolder;
use Illuminate\Support\Facades\Storage;

class CompanyService {

    public function all()
    {
        $company = Company::withCount('listings')
            ->with('industry')
            ->paginate(15);

        return $company;
    }
    
    
    
    
    
    public function add($data)
    {
        $d = $data['company'];
        $logo = null;

        if( is_null( $data['logo'] ) ) {
            $logo = 'placeholder.png';
        } else {
            $fileObj = TemporaryFolder::where( 'folder', $data['logo'] )->first();
            
            $folder = $fileObj->folder;
            $file = $fileObj->file;

            $f = Storage::get('public/tmp/'.$folder.'/'.$file);
            
            // mv the file to permanent disk
            Storage::disk('public')
                ->writeStream(
                    'logos/'.$folder.'/'.$file,
                    Storage::disk('public')
                        ->readStream('tmp/'.$folder.'/'.$file)
                );

            Storage::disk('public')->deleteDirectory('tmp/'.$folder);
            $logo = 'logos/'.$folder.'/'.$file;
        }

        

        $company = Company::updateOrCreate(
            [
                'name' => $d['name'],
                'author' => $d['author']
            ],
            [
                'uuid' => Str::uuid(),
                'email' => $d['email'],
                'slug' => Str::slug($d['name']),
                'url' => $d['url'],
                'industry_id' => $d['industry_id'],
                'logo' => $logo,
                'address' => $d['address'],
                'city' => $d['city'],
                'state' => $d['state'],
                'zip' => $d['zip'],
                'description' => $d['description']
            ]
        );
        return $company;
    }
    
    
    
    
    
    public function logo($file) {

        return $file;

    }
    
    
    
    
    
    public function single($uuid, $slug)
    {
        $company = Company::with('listings')
        ->with('industry')
        ->where([
            'uuid' => $uuid,
            'slug' => $slug
        ])
        ->first();

        return $company;

    }

}