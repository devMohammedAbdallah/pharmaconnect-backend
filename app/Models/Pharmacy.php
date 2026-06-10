<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = [
        'name',
        'location',
        'phone',
        'working_hours',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'medicine_pharmacy')
                    ->withPivot('stock', 'stock_status')
                    ->withTimestamps();
    }
}