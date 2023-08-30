<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
