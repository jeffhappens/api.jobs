<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Str;

class CompanyService {

    public function all()
    {

        $company = Company::withCount('listings')->with('industry')->paginate(15);
        return $company;
        
    }


    public function add($data)
    {
        
        $fields = ['user_id', 'name', 'address', 'industry_id'];

        $company = new Company;

        foreach($fields as $key => $value) {
            $company->{$value} = $data['company'][$value];
        }
        $company->logo = $data['logo'];
        $company->save();
        return $company;

    }


    public function logo($file) {

        return $file;

    }

}