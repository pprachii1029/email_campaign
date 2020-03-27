<?php

namespace Email_Campaign\Http\Controllers;

use Illuminate\Http\Request;
use Email_Campaign\Group;
use Email_Campaign\Contact;
use Email_Campaign\History;
use Email_Campaign\Template;
use Email_Campaign\Template_video;
use Email_Campaign\Template_photo;
use Email_Campaign\Template_url;
use Email_Campaign\Template_snapshot;
use Email_Campaign\Pre_final_template_video;
use Email_Campaign\Group_member;
use Email_Campaign\EmailTemplate;
use Email_Campaign\NylasAuthEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class TemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function templates(){
        $data = Template::get_all_templates_table(Auth::user()->id);
        return view('templates')->with($data);
    }

    public function add_template(){
        return view('add_template');
    }

    
    public function unlayer_template(){
        return view('unlayer_template');
    }


    

    public function save_template(Request $request){

        $input      = $request->all();
       //echo "<pre>"; print_R($input);exit;
        $userid     = Auth::user()->id;
        $unlink     = (array)json_decode($input['unlink'],true);
        
        foreach($unlink as $row){
            if(file_exists($row)){
                unlink($row);
            }
        }

        $video      = upload_multiple($request,'video','template/video');
        $url        = upload_base64($input,'url_ss','template/url/');
        $photo      = upload_multiple($request,'photo','template/photo');
        $snapshot   = upload_base64($input,'snapshot_ss','template/snapshot/');

        $insert = [
            'title'         => $input['title'], 
            'description'   => $input['description'], 
            'user_id'       => $userid,
            'intro_audio'   => $input['intro_audio'] ? $input['intro_audio'] : '',
        ];

        $template_id = Template::create($insert)->id;
        $video_arr   = [];
        foreach($video as $key => $row){
            $video_arr[] = [
                'template_id' => $template_id,
                'video'       => $row,
                'start'       => $input['video_start'][$key],
                'duration'    => $input['video_duration'][$key],
                'mute'        => $input['mute'][$key],
                'order'       => $input['order_video'][$key],
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ];
        }
        $photo_arr = [];
        foreach($photo as $key => $row){
            $photo_arr[] = [
                'template_id' => $template_id,
                'photo'       => $row,
                'duration'    => $input['photo_duration'][$key],
                'order'       => $input['order_photo'][$key],
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ];
        }
        $url_arr = [];
        foreach($url as $key => $row){
            $url_arr[] = [
                'template_id'   => $template_id,
                'url'           => $row,
                'duration'      => $input['url_duration'][$key],
                'order'         => $input['order_url'][$key],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
        }
        $snapshot_arr = [];
        foreach($snapshot as $key => $row){
            $snapshot_arr[] = [
                'template_id'   => $template_id,
                'snapshot'      => $input['snapshot'][$key],
                'snapshot_ss'   => $row,
                'duration'      => $input['snapshot_duration'][$key],
                'order'         => $input['order_snap'][$key],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ];
        }

        Template_snapshot::insert($snapshot_arr);        
        Template_video::insert($video_arr);
        Template_photo::insert($photo_arr);
        Template_url::insert($url_arr);

        $template    = Template::get_template_content($template_id);
        
        $final       = [];
        foreach($template['videos'] as $row){
            $row['content'] = 'video';
            $final[]        = $row; 
        }
        foreach($template['photos'] as $row){
            $row['content'] = 'photo';
            $final[]        = $row; 
        }
        foreach($template['urls'] as $row){
            $row['content'] = 'url';
            $final[]        = $row; 
        }
        foreach($template['snapshots'] as $row){
            $row['content'] = 'snapshot';
            $final[]        = $row; 
        }
        
        array_multisort(array_column($final, 'order'), SORT_ASC, $final);

        $order = 0;
        $sep_arr[$order] = [];
        foreach ($final as $key => $value) {
            if($value['content']=='video'){
                $order++;
                $sep_arr[$order][] = $value;
                $order++;
            }else{
                $sep_arr[$order][] = $value;
            }
        }
        // echo '<pre>';print_r($sep_arr) exit();
        if(count($final)>0){
            foreach ($sep_arr as $key => $value) {
            	$mute = (@$value[0]['content']!='video') ? 1 : @$value[0]['mute'];
                if(count($value)>0){
                    $sep_video[] = ['video'=> make_video($value),'content'=>$value[0]['content'],'mute'=>$mute];
                }
            }
            foreach ($sep_video as $key => $value) {
            	Pre_final_template_video::create(['video'=>$value['video'],'template_id'=>$template_id,'content'=>$value['content'],'mute'=>@$value['mute']]);
            }
        }

        return redirect()->route('arrange_template_content',['template_id'=>Crypt::encrypt($template_id)]);
    }

    public function arrange_template_content(Request $request){
        $input = $request->all();
        $template_id  = Crypt::decrypt($input['template_id']);
        $final_vidoes = Pre_final_template_video::where(['template_id'=>$template_id])->get()->toArray();
        
        return view('arrange_template_content')->with(['template'=>$final_vidoes,'template_id'=>$template_id]);
    }

    public function make_video(Request $request){
        $input      = $request->all();
        $template_id= $input['template_id'];
        $final 		= [];

        foreach ($input['video'] as $key => $value) {
        	Pre_final_template_video::where(['id'=>$input['id'][$key]])->update(['audio'=>$input['audio'][$key],'video_recorded'=>$input['video_recorded'][$key]]);
            if(!empty($input['audio'][$key])){
                $final[] = add_audio_to_video($value,$input['audio'][$key]);
            }else{
                $final[] = add_video_over_video($value,$input['video_recorded'][$key]);
            }
        } 

        $final_video = concat_all_vdos($final);
		Template::where(['id'=>$template_id])->update(['final_video'=>$final_video]);

        if($input['submit']=='save'){
            return redirect()->route('templates');
        }else{
            return redirect()->route('preview_template',['id'=>Crypt::encrypt($template_id)]);
        }
    }

    public function preview_template(Request $request){
        $input      = $request->all();

        $template_id= Crypt::decrypt($input['id']);
        $template   = Template::get_template_detail($template_id);

        return view('preview_template')->with(json_decode(json_encode($template),true));
    }

    public function final_video(Request $request){
        $input      = $request->all();
       /* $link       = URL('shell.php');
        $path       = "public/template/".rand(12345,98765).time().".mp4";*/
        
        if(!empty($input['recorded_file'])){
            /*$post       = ['method'=>'add_audio_in_video',"video"=>$input['final_video'],'audio'=>$input['recorded_file'],'path'=>$path];
            $content    = hit_curl_post($link,$post);*/
            $path = add_audio_to_video($input['final_video'],$input['recorded_file']);
            Template::where(['id'=>$input['template_id']])->update([
                'final_video'=>$path,
                'audio'      =>$input['recorded_file']
            ]);
        }
        return redirect()->route('templates');
    }

    public function test(){
        return view('video');
    }
    //---
    public function email_drip(Request $request){
        $input              = $request->all();
        $pagination         = 1000;
        $data['user_id']    = Auth::user()->id;
        $email_template     = EmailTemplate::get_all_email_templates(Auth::user()->id,$pagination);
        $group_data         = Group::get_group_which_have_members(Auth::user()->id);
        $video_template     = Template::get_all_templates_table(Auth::user()->id,$pagination);
        $connected_acc      = NylasAuthEmails::get_auth_email_data($data);
        
        $email_data['contacts']       = $group_data;
        $email_data['email_template'] = $email_template['data'];
        $email_data['video_template'] = $video_template['data'];
        $email_data['accounts']       = $connected_acc;
        // echo "<pre>";
        // print_r($email_data);
        return view('email_drip')->with($email_data);
    }
}
