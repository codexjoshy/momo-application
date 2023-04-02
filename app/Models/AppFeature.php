<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppFeature extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $casts = [
        "created_at"=> 'date',
        "updated_at"=> 'date',
        "other_info"=> 'array'
    ];


    /**
     * Get all of the customers for the MomoSchedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(AppFeatureCustomer::class, 'app_feature_id', 'id');
    }
}
