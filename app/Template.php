<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Template extends Model
{
    protected $fillable = [
        'title', 'description','user_id','snapshot','snapshot_duration','video','video_duration','url','url_duration','photo','photo_duration','final_video','intro_audio','outro_audio'
    ];

    public static function get_all_templates($id){
        $data =  DB::table('templates')->where(['user_id'=>$id,'status'=>1])->orderBy('id','DESC')->get()->toArray();
        return $data;
    }
    
    public static function get_all_templates_table($id,$pagination = 20){
        if(!empty(@$_GET['search'])){
            $data = Template::orderBy('id', 'desc')->where(['user_id'=>$id,'status'=>1])->where('title','LIKE','%'.$_GET['search'].'%')->paginate($pagination)->toArray();
        }else{
            $data = Template::orderBy('id', 'desc')->where(['user_id'=>$id,'status'=>1])->paginate(10)->toArray();
        }
        return $data;
    }

    public static function get_template_detail($id){
        $data = DB::table('templates')->where('id','=',$id)->get()->first();
        return $data;
    }

    public static function delete_template($id){
        DB::table('templates')->where('id','=',$id)->update(['status' => 0]);
        return 200;
    }

    public static function get_template_content($id){
        $template = DB::table('templates')->where('id','=',$id)->get()->first();
        if($template){
            $template->videos = DB::table('template_videos')->where('template_id','=',$id)->get()->toArray();
            $template->urls   = DB::table('template_urls')->where('template_id','=',$id)->get()->toArray();
            $template->photos = DB::table('template_photos')->where('template_id','=',$id)->get()->toArray();
            $template->snapshots = DB::table('template_snapshots')->where('template_id','=',$id)->get()->toArray();
            return json_decode(json_encode($template),true);
        }else{
            return [];
        }
    }
}
