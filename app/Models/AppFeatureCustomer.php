<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppFeatureCustomer extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        "created_at" => 'datetime',
        "updated_at" => 'datetime',
        "other_info" => 'array'
    ];
}
