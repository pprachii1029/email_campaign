<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;

class Template_url extends Model
{
    protected $fillable = [
        'template_id','url','duration','order'
    ];
}
