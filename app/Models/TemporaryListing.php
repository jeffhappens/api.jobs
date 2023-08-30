<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryListing extends Model
{
    use HasFactory;

    protected $fillable = ['user_uuid', 'title', 'industry_id', 'company_id', 'apply_link', 'job_type_id', 'description'];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
