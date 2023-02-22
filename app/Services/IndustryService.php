<?php

namespace App\Services;

use App\Models\Industry;

class IndustryService {

    public function all()
    {
        $industries = Industry::all();
        return $industries;
    }
}