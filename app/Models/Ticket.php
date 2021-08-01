<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Ticket extends Model
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    use HasFactory;

    protected $fillable = 
    [
        "ticket_id",
        "member_id",
        "subject",
        "content",
        "status",
    ];
}
