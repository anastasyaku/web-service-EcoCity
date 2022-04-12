<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use \Parental\HasChildren;

    protected $fillable = [
        "latitude",
        "longitude",
        "address",
        "description",
        "photo_file",
        "type",

        "sender_id",
        "status",

        "name",
        "due",
        "site",

        "category",
        "phone",
        "email",

        "owner",
        "full"
    ];
}
