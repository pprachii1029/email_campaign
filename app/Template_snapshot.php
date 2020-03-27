<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;

class Template_snapshot extends Model
{
    protected $fillable = [
        'template_id','snapshot','snapshot_ss','duration','order'
    ];
}
