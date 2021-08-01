<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Shipment extends Model
{
    use HasFactory;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i:s');
    }

    protected $fillable = ["member_id", "shipmentno", "transaction_id", "content", "cost", "status", "rejectcause"];

    //Relations
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
