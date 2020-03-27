<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Suppression extends Model
{
    protected $fillable = [
        'user_id','group_id','host_name'
    ];

    public static function get_all_suppression($id){
        $data = DB::table('suppressions')->where('user_id','=',$id)->get();
        return $data;
    }
}
