<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'stock',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function pharmacies()
    {
        return $this->belongsToMany(Pharmacy::class, 'medicine_pharmacy')
                    ->withPivot('stock', 'stock_status')
                    ->withTimestamps();
    }
}