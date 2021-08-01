<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mainsection extends Model
{
    use HasFactory;
    protected $fillable = ["msection_name", "section_img", "description"];
}
