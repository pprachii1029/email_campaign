<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class History extends Model
{
    protected $fillable = [
        'message', 'subject','user_id','contact_id','group_id','template_id','thread'
    ];

    public static function get_sent_messages($id){
        $data = DB::table('histories')->select(DB::raw('contacts.*,templates.title,templates.final_video,groups.picture as group_pic,histories.id as id,histories.template_id,histories.subject,histories.message,histories.created_at'))->leftJoin('contacts','contacts.id','=','histories.contact_id')->leftJoin('group_members','group_members.contact_id','=','contacts.id')->leftJoin('groups','groups.id','=','group_members.group_id')->leftJoin('templates','templates.id','=','histories.template_id')->where(['histories.user_id'=>$id,'histories.group_id'=>0])->orderBy('histories.id','DESC')->paginate(10)->toArray();
        return $data;
    }

    public static function get_sent_campaigns($id){
        $data = DB::table('histories')->select(DB::raw('contacts.*,templates.title,groups.picture as group_pic,histories.id as id,histories.template_id,histories.subject,histories.message,histories.thread,groups.group_name,templates.final_video,histories.created_at'))->leftJoin('contacts','contacts.id','=','histories.contact_id')->leftJoin('group_members','group_members.contact_id','=','contacts.id')->leftJoin('groups','groups.id','=','group_members.group_id')->leftJoin('templates','templates.id','=','histories.template_id')->where(['histories.user_id'=>$id])->where('histories.group_id','!=',0)->orderBy('histories.id','DESC')->groupBy('histories.thread')->paginate(10)->toArray();
        //groupBy('histories.thread')->
        return $data;
    }

    public static function get_total_sentusers($thread){
        ini_set('memory_limit', '-1');

        $wordlist = DB::table('histories')->where('thread', '=', $thread)->get();
        $wordCount = $wordlist->count();

        // $data = DB::table('histories')->select(DB::raw('contacts.*,templates.title,groups.picture as group_pic,histories.id as id,histories.template_id,histories.subject,histories.message,groups.group_name,templates.final_video,histories.created_at'))->leftJoin('contacts','contacts.id','=','histories.contact_id')->leftJoin('group_members','group_members.contact_id','=','contacts.id')->leftJoin('groups','groups.id','=','group_members.group_id')->leftJoin('templates','templates.id','=','histories.template_id')->where(['histories.thread'=>$thread])->where('histories.group_id','!=',0);
       // print_r($wordCount);exit;
        return $wordCount;
      
    }

      public static function get_sent_sentusers($thread){
        ini_set('memory_limit', '-1');

        $wordlist  = DB::table('histories')->where('thread', '=', $thread)->where('sent_status', '=', 1)->get();
        $wordCount = $wordlist->count();
        
        return $wordCount;
      
    }

    

}
