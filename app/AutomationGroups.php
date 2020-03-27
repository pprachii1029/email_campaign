<?php

namespace Email_Campaign;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class AutomationGroups extends Model
{
    protected $table = 'automation_groups';
    protected $fillable = [
        'sender_id','automation_name','status'
    ];

    public static function get_automation_groups($id){
        $data = DB::table('automation_groups')->select(DB::raw('*'))->where(['sender_id'=>$id])->orderBy('automation_groups.id','DESC')->paginate(10)->toArray();
       
        return $data;
    }
    public static function get_automation_group_details($id){
    	return DB::table('automation_groups')->select(DB::raw('*'))->where(['id'=>$id])->orderBy('automation_groups.id','DESC')->first();  
    }
}