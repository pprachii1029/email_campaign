<?php

namespace Email_Campaign\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Email_Campaign\Group;
use Email_Campaign\AutomationEmails;
use Email_Campaign\Template;
use Email_Campaign\Template_photo;
use Email_Campaign\Template_snapshot;
use Email_Campaign\Notification;
use Email_Campaign\Template_video;
use Email_Campaign\Template_url;
use Email_Campaign\Contact;
use Email_Campaign\History;
use Email_Campaign\Group_member;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function genrate_video(Request $request){
    	$id 	= Crypt::decrypt($request->all()['id']);
    	$history= History::where(['id'=>$id])->get()->first()->toArray();
    	$contact= Contact::where(['id'=>$history['contact_id']])->get()->first()->toArray();
    	if(empty($history['video_link'])){
    		$photos     = Template_photo::where(['template_id'=>$history['template_id']])->get()->toArray();
    		$videos    	= Template_video::where(['template_id'=>$history['template_id']])->get()->toArray();
        	$urls       = Template_url::where(['template_id'=>$history['template_id']])->get()->toArray();
        	$snapshots  = Template_snapshot::where(['template_id'=>$history['template_id']])->get()->toArray();
        	$template  	= Template::where(['id'=>$history['template_id']])->get()->first()->toArray();

        	$final_sqs  = [];

        	foreach($photos as $row){
	            $final_sqs[] = [
	                'id'            => $row['id'],
	                'template_id'   => $row['template_id'],
	                'photo'         => $row['photo'],
	                'duration'      => $row['duration'],
	                'created_at'    => $row['created_at'],
	                'updated_at'    => $row['updated_at'],
	                'order'         => $row['order'],
	                'content'       => 'photo',
	            ];
	        }
	        foreach($urls as $row){
	            $final_sqs[] = [
	                'id'            => $row['id'],
	                'template_id'   => $row['template_id'],
	                'url'           => $row['url'],
	                'duration'      => $row['duration'],
	                'created_at'    => $row['created_at'],
	                'updated_at'    => $row['updated_at'],
	                'order'         => $row['order'],
	                'content'       => 'url',
	            ];
	        }
	        foreach($videos as $row){
	            $final_sqs[] = [
	                'id'            => $row['id'],
	                'template_id'   => $row['template_id'],
	                'video'         => $row['video'],
	                'duration'      => $row['duration'],
	                'created_at'    => $row['created_at'],
	                'updated_at'    => $row['updated_at'],
	                'order'         => $row['order'],
	                'content'       => 'video',
	                'start'         => $row['start'],
	                'mute'          => $row['mute'],
	            ];
	        }

	        foreach($snapshots as $row){
	            if($row['snapshot']=='Website' && !empty($contact['website_ss'])){
	                $row['snapshot_ss'] = base64_to_jpeg(explode(',',$contact['website_ss'])[1],'template/snapshot/');
	            }else if($row['snapshot']=='Facebook' && !empty($contact['facebook_ss'])){
	                $row['snapshot_ss'] = base64_to_jpeg(explode(',',$contact['facebook_ss'])[1],'template/snapshot/');
	            }else if($row['snapshot']=='Linked In' && !empty($contact['linkedin_ss'])){
	                $row['snapshot_ss'] = base64_to_jpeg(explode(',',$contact['linkedin_ss'])[1],'template/snapshot/');
	            }
	            $final_sqs[] = [
	                'id'            => $row['id'],
	                'template_id'   => $row['template_id'],
	                'snapshot'      => $row['snapshot'],
	                'snapshot_ss'   => $row['snapshot_ss'],
	                'duration'      => $row['duration'],
	                'created_at'    => $row['created_at'],
	                'updated_at'    => $row['updated_at'],
	                'order'         => $row['order'],
	                'content'       => 'snapshot',
	            ];
	        }
	        $order = array_column($final_sqs, 'order');
        	array_multisort($order, SORT_ASC, $final_sqs);

        	if(count($final_sqs)>0){
	            $final_video 				= make_video($final_sqs);
	            $final_video 				= add_audio_to_video($final_video,$template['audio']);
	            $template['final_video'] 	= $final_video;
	        }

	        if(!empty($contact['audio'])){
	            $video2 = $template['final_video'];
	            $video1 = make_video_with_user_audio($video2,$contact['audio']);
	            $template['final_video']  =  join_two_videos($video1,$video2);
	        }
	        History::where(['id'=>$id])->update([
	            'video_link' => $template['final_video']
	        ]);
	        $link = $template['final_video'];
    	}else{
			$link = $history['video_link'];
    	}

    	return URL($link);
    }

    public function open_video(Request $request){
    	$id 	= Crypt::decrypt($request->all()['id']);
    	$history= History::where(['id'=>$id])->get()->first()->toArray();
    	$template= Template::where(['id'=>$history['template_id']])->get()->first()->toArray();
    	$contact= Contact::where(['id'=>$history['contact_id']])->get()->first()->toArray();
    	
    	$notification_id = Notification::create([
    		'user_id'=>$history['user_id'],
    		'contact_id'=>$history['contact_id'],
    		'history_id'=>$history['id'],
    		'message'=>$contact['first_name'].' '.$contact['last_name']." have watched your ".$template['title'],
    		'time'=>"00:00",
    		'message_type'=>1,
    		'is_read'=>0
    	])->id;

    	return view('play_video')->with(['link'=>$history['video_link'],'notification_id'=>$notification_id]);
    }
    public function open_automation_video(Request $request){
    	$id 	= Crypt::decrypt($request->all()['id']);
    	$history= AutomationEmails::where(['id'=>$id])->get()->first()->toArray();
    	//$template= Template::where(['id'=>$history['template_id']])->get()->first()->toArray();
    	$contact= Contact::where(['id'=>$history['receiver_id']])->get()->first()->toArray();
    	
    	$notification_id = Notification::create([
    		'user_id'=>$history['sender_id'],
    		'contact_id'=>$history['receiver_id'],
    		'history_id'=>$history['id'],
    		'message'=>$contact['first_name'].' '.$contact['last_name']." have watched your ",
    		'time'=>"00:00",
    		'message_type'=>1,
    		'is_read'=>0
    	])->id;

    	return view('play_automation_video')->with(['link'=>$history['final_video'],'notification_id'=>$notification_id]);
    }
}

        
