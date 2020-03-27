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
use Email_Campaign\Group_member;
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

    public function save_template(Request $request){
        $input      = $request->all();
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
        $outro      = upload_multiple($request,'outro_picture','template/outro_picture');
        $snapshot   = upload_base64($input,'snapshot_ss','template/snapshot/');
        
        $outro_video= make_outro_video($outro,$input['outro_audio']);

        $insert = [
            'title'         => $input['title'], 
            'description'   => $input['description'], 
            'user_id'       => $userid,
            'intro_audio'   => $input['intro_audio'] ? $input['intro_audio'] : '',
            'outro_audio'   => ($outro_video) ? $outro_video : '',
        ];

        $template_id = Template::create($insert)->id;

        foreach($video as $key => $row){
            Template_video::create([
                'template_id' => $template_id,
                'video'       => $row,
                'start'       => $input['video_start'][$key],
                'duration'    => $input['video_duration'][$key],
                'mute'        => $input['mute'][$key],
            ]);
        }

        foreach($photo as $key => $row){
            Template_photo::create([
                'template_id' => $template_id,
                'photo'       => $row,
                'duration'    => $input['photo_duration'][$key],
            ]);
        }

        foreach($url as $key => $row){
            Template_url::create([
                'template_id'   => $template_id,
                'url'           => $row,
                'duration'      => $input['url_duration'][$key],
            ]);
        }

        foreach($snapshot as $key => $row){
            Template_snapshot::create([
                'template_id'   => $template_id,
                'snapshot'      => $input['snapshot'][$key],
                'snapshot_ss'   => $row,
                'duration'      => $input['snapshot_duration'][$key],
            ]);
        }

        return redirect()->route('arrange_template_content',['template_id'=>Crypt::encrypt($template_id)]);
    }

    public function arrange_template_content(Request $request){
        $input = $request->all();
        $template_id = Crypt::decrypt($input['template_id']);
        $template    = Template::get_template_content($template_id);
        
        $final       = [];
        foreach($template['videos'] as $row){
            $row['content'] = 'video';
            $final[]   = $row; 
        }
        foreach($template['photos'] as $row){
            $row['content'] = 'photo';
            $final[]   = $row; 
        }
        foreach($template['urls'] as $row){
            $row['content'] = 'url';
            $final[]   = $row; 
        }
        foreach($template['snapshots'] as $row){
            $row['content'] = 'snapshot';
            $final[]   = $row; 
        }
        
        return view('arrange_template_content')->with(['template'=>$final,'template_id'=>$template_id]);
    }

    public function make_video(Request $request){
        $input      = $request->all();
        $pre_sqs    = json_decode($input['pre_pos'],true);
        $new_sqs    = explode(',',$input['new_pos']);
        $video      = [];
        $photo      = [];
        $url        = [];
        $snapshot   = [];
        $final_sqs  = [];
        $template_id= $input['template_id'];

        if(@$new_sqs[0]==''){
        	$new_sqs = [];
	        for($i=0;$i<count($pre_sqs);$i++){
	        	$new_sqs[] = $i;
	        }
        }
        
        foreach($new_sqs as $key => $row){
            $final_sqs[] = $pre_sqs[$row];
            if($pre_sqs[$row]['content']=='video'){
                $video[]    = ['id'=>$pre_sqs[$row]['id'],'start'=>$pre_sqs[$row]['start'],'duration'=>$pre_sqs[$row]['duration'],'order'=>$key];
            }else if($pre_sqs[$row]['content']=='photo'){
                $photo[]    = ['id'=>$pre_sqs[$row]['id'],'duration'=>$pre_sqs[$row]['duration'],'order'=>$key];
            }else if($pre_sqs[$row]['content']=='url'){
                $url[]      = ['id'=>$pre_sqs[$row]['id'],'duration'=>$pre_sqs[$row]['duration'],'order'=>$key];
            }elseif($pre_sqs[$row]['content']=='snapshot'){
                $snapshot[] = ['id'=>$pre_sqs[$row]['id'],'duration'=>$pre_sqs[$row]['duration'],'order'=>$key];
            }
        }

        foreach($video as $row){
            Template_video::where(['id'=>$row['id']])->update([
                'start'       => $row['start'],
                'duration'    => $row['duration'],
                'order'       => $row['order'],
            ]);
        }
        
        foreach($photo as $key => $row){
            Template_photo::where(['id'=>$row['id']])->update([
                'duration'    => $row['duration'],
                'order'       => $row['order'],
            ]);
        }

        foreach($url as $key => $row){
            Template_url::where(['id'=>$row['id']])->update([
                'duration'    => $row['duration'],
                'order'       => $row['order'],
            ]);
        }

        foreach($snapshot as $key => $row){
            Template_snapshot::where(['id'=>$row['id']])->update([
                'duration'    => $row['duration'],
                'order'       => $row['order'],
            ]);
        }

        if(count($final_sqs)>0){
            $final_video = make_video($final_sqs);
            Template::where(['id'=>$template_id])->update([
                'final_video'=>$final_video
            ]);
        }
        
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

    public function save_template_old(Request $request){
        $input      = $request->all();
        $userid     = Auth::user()->id;
        $pre_sqs    = json_decode($input['pre_pos'],true);
        $new_sqs    = explode(',',$input['new_pos']);
        $final_sqs  = [];
        
        foreach($new_sqs as $key => $row){
            $final_sqs[] = $pre_sqs[$row];
            
            if($pre_sqs[$row]['content']=='video'){
                $video[]    = ['url'=>$pre_sqs[$row]['url'],'start'=>0,'duration'=>3,'order'=>$key];
            }else if($pre_sqs[$row]['content']=='photo'){
                $photo[]    = ['url'=>$pre_sqs[$row]['url'],'duration'=>3,'order'=>$key];
            }else if($pre_sqs[$row]['content']=='url'){
                $url[]      = ['url'=>$pre_sqs[$row]['url'],'duration'=>3,'order'=>$key];
            }
        }
        
        $insert = [
            'title'         =>$input['title'], 
            'description'   =>$input['description'], 
            'snapshot'      =>$input['snapshot'],
            'user_id'       =>$userid
        ];
        
        if(count($final_sqs)>0){
            $insert['final_video'] = make_video($final_sqs);
        }
        
        $template_id = Template::create($insert)->id;

        foreach($video as $row){
            Template_video::create([
                'template_id' => $template_id,
                'video'       => $row['url'],
                'start'       => $row['start'],
                'duration'    => $row['duration'],
                'order'       => $row['order'],
            ]);
        }

        foreach($photo as $key => $row){
            Template_photo::create([
                'template_id' => $template_id,
                'video'       => $row['url'],
                'duration'    => $row['duration'],
                'order'       => $row['order'],
            ]);
        }

        foreach($url as $key => $row){
            Template_url::create([
                'template_id' => $template_id,
                'video'       => $row['url'],
                'duration'    => $row['duration'],
                'order'       => $row['order'],
            ]);
        }

        return redirect()->route('templates');
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
}
