<?php

namespace Email_Campaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AutomationGroup extends Model
{
    protected $table = 'automation_group';
    protected $fillable = [
        'group_id','sender_id','receiver_id','email_subject','video_template','email_template','waitDate','waitTime','send_at','status'
    ];

     public static function get_automation_group($id){
        $data = DB::table('automation_group')->select(DB::raw('*'))->where(['group_id'=>$id])->orderBy('automation_group.id','ASC')->paginate(10)->toArray();
       
        return $data;
    }
    public static function get_all_groups($id){
        $data = DB::table('automation_group')->where(['group_id'=>$id,'status'=>0])->orderBy('automation_group.id','ASC')->get()->toArray();
        return $data;
    }
    public static function get_single_group($main_id){
    	$data = DB::table('automation_group')->select(DB::raw('*'))->where(['group_id'=>$main_id,'status'=>0])->orderBy('automation_group.id','ASC')->first();
    	return $data;
    }
    public static function get_enabled_automation_groups(){
    	$data = DB::table('automation_group')->select(DB::raw('*'))->where('status',1)->orderBy('automation_group.send_at','ASC')->get()->toArray();
    	return $data;
    }
    public static function get_enabled_single_automation_groups(){
        $data = DB::table('automation_group')->select(DB::raw('*'))->where(['status'=>1,'ready'=>'0'])->orderBy('automation_group.send_at','ASC')->first();
        return $data;
    }
    public static function get_ready_group(){
        $data = DB::table('automation_group')->select(DB::raw('*'))->where(['status'=>1,'ready'=>1])->orderBy('automation_group.send_at','ASC')->first();
        return $data;
    }
    public static function check_template_exist($array){
        $data = DB::table('automation_group')->select(DB::raw('*'))->where($array)->orderBy('automation_group.id','ASC')->get()->toArray();
        return $data;
    }

}
