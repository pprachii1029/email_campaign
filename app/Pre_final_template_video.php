<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;

class Pre_final_template_video extends Model
{
    protected $fillable = [
        'template_id', 'video','audio','mute','content'
    ];
}
