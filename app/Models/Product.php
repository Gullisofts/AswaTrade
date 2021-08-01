<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ["mainsectionid", "section_id", "productname", "produnit", "produnit", "prodcode", "description", "spec", "manufacturer", "discount", "productimages", "price", "qty", "rating", "reviews"];

    public function scopeDiscount($query)
    {
        return $query->where("discount", ">", 0);
    }

}
