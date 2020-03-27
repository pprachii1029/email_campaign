<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Group extends Model
{
    protected $fillable = [
        'group_name', 'picture','user_id'
    ];
    
    public static function get_all_groups($id){
        return DB::table('groups')->select('groups.*',DB::raw("(SELECT count(id) FROM group_members WHERE group_members.group_id = groups.id) as countMembers"))->where(['user_id'=>$id])->get()->toArray();
    }
    public static function get_group_which_have_members($id){
    	//SELECT groups.* ,count(group_members.id) FROM groups INNER JOIN group_members ON group_members.group_id = groups.id WHERE groups.user_id= 1 GROUP BY groups.id
    	return  DB::table('groups')->select( 'groups.*')->join('group_members','group_members.group_id','=','groups.id')->where(['groups.user_id'=>$id])->groupBy('groups.id')->get()->toArray();
    }

    public static function get_group_details($id){
        return DB::table('groups')->where(['id'=>$id])->get()->first();;
    }
}
