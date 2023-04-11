<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Industry;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['expired'];

    protected $casts = [
        'created_at' => 'date:m/d/Y',
        'expires_at' => 'date:m/d/Y',
        'renewed_on' => 'date:m/d/Y',

    ];

    public function getExpiredAttribute()
    {
        return $this->expires_at < now(); //or however you want to manipulate it
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function industry() {
        return $this->belongsTo(Industry::class);
    }

    public function type() {
        return $this->belongsTo(JobType::class);
    }

}
