<?php

namespace Email_Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class NylasAuthEmails extends Model
{
    protected $table = 'nylasAuthEmails';
    protected $fillable = [
        'user_id', 'email', 'access_token', 'provider'
    ];

    public static function get_auth_email_data($array){
    	return DB::table('nylasAuthEmails')->select(DB::raw('*'))->where($array)->first(); 
    }
    public static function get_all_auth_email_data($array){
    	return DB::table('nylasAuthEmails')->select(DB::raw('*'))->where($array)->get()->toArray(); 
    }
    public static function update_auth_email($array,$response_type){
    	return DB::table('nylasAuthEmails')->where($array)->update($response_type);
    }
}
