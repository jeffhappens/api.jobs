<?php

namespace App\Services;

use App\Models\Industry;

class IndustryService {

    public function all()
    {
        $industries = Industry::withCount('listings')->get();
        return $industries;
    }
}