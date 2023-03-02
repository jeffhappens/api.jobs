<?php

namespace App\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'date:m/d/Y'

    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
