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
        $fields = ['user_id', 'name', 'address', 'industry_id', 'description'];

        $company = new Company;

        foreach($fields as $key => $value) {

            $company->{$value} = $data['company'][$value];

        }

        if(is_null($data['logo'])) {

            $company->logo = 'placeholder.png';

        } else {

            $fileObj = TemporaryFolder::where('folder', $data['logo'])->first();
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

            $company->logo = 'logos/'.$folder.'/'.$file;
            
        }
        $company->save();
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