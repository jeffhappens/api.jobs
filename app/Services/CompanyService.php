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
        $fields = ['user_id', 'name', 'address', 'industry_id', 'logo'];

        $logo = Str::replace('C:\\fakepath\\', '', $data['logo']);

        $company = new Company;

        foreach($fields as $key => $value) {
            if($value === 'logo') {
                $company->{$value} = $logo;
            } else {
                $company->{$value} = $data[$value];
            }
        }
        $company->save();
        return $company;

    }


    public function logo($file) {

        return $file;

    }

}