<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailTemplate extends Model
{
    protected $table = 'template_email';
    protected $fillable = [
        'user_id','email_html','template_name','template_image'
    ];
    
    public static function get_all_email_templates($id,$paginate = 10){
        $data = DB::table('template_email')->select(DB::raw('*'))->where(['user_id'=>$id])->orderBy('template_email.id','DESC')->paginate($paginate)->toArray();
       
        return $data;
    	
        //return DB::table('template_email')->select(DB::raw('*'))->where(['user_id'=>$id])->orderBy('id', 'desc')->get()->toArray();
    }
    public static function get_single_email_templates_without_user($template_id ){
        return DB::table('template_email')->where('id','=',$template_id)->first();
    }
    public static function get_single_email_templates($template_id ,$user_id){
        return DB::table('template_email')->where(['id'=>$template_id],['user_id'=>$user_id])->limit(1)->get()->toArray();
    }

    public static function delete_email_template($template_id ,$user_id){
    	return DB::table('template_email')->where(['id'=>$template_id],['user_id'=>$user_id])->delete();
    }
}
