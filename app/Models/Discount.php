<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'value', 'start_date', 'end_date'
    ];

    public function discountMappings(): HasMany
    {
        return $this->hasMany(DiscountMapping::class, 'discount_id');
    }
}
