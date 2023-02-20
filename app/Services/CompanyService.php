<?php

namespace App\Services;

use App\Models\Company;

class CompanyService {

    public function all()
    {

        $company = Company::withCount('listings')->get();
        return $company;
        
    }

}