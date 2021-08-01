<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        "sitename",
        "sitestatus",
        "registerstatus",
        "revisecomments",
        "currency",
        "regions",
        "sitelink",
        "footerdescrip",
        "sitestatus",
        "emails",
        "phones",
        "phonestatus",
        "emailstatus",
        "facebooklink",
        "twitterlink",
        "instagramlink",
    ];
}
