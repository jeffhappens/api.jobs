<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->expires_at < now();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function type()
    {
        return $this->belongsTo(JobType::class);
    }
}
