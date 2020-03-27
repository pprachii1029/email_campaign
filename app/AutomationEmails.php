<?php

namespace Email_Campaign;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AutomationEmails extends Model
{
    protected $table = 'automation_emails';
    protected $fillable = [
        'group_id', 'sender_id', 'receiver_id', 'final_video', 'status'
    ];

    public static function get_group_single_email($group_id){
    	DB::table('automation_group')->where(['group_id'=>$group_id,'ready'=>0])->first();
    }
    public static function get_group_email($group_id){
    	DB::table('automation_group')->where(['group_id'=>$group_id,'status'=>0])->get()->toArray();
    }
}
