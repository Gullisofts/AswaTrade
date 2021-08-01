<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory;

    protected $fillable=["fullname", "ip", "email", "phone", "password", "address1", "address2", "governorate", "city", "zip", "reset_token", "reset_time", "verification_token", "verification_status"];

    //Relations
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
