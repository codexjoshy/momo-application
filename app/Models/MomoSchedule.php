<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class MomoSchedule extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $casts = [
        "created_at"=> 'date',
        "updated_at"=> 'date',
    ];


    /**
     * Get all of the customers for the MomoSchedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(MomoScheduleCustomer::class, 'momo_schedule_id', 'id');
    }
}
