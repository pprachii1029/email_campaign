<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;

class Template_photo extends Model
{
    protected $fillable = [
        'template_id','photo','duration','order'
    ];
}
