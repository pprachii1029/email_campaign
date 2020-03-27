<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group_member extends Model
{
    protected $fillable = [
        'group_id', 'contact_id','user_id'
    ];
}
