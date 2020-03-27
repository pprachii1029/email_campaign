<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Template_video extends Model
{
    protected $fillable = [
        'template_id','video','duration','order','start','mute'
    ];

}
