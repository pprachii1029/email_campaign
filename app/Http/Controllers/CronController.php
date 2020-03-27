<?php

namespace Email_Campaign\Http\Controllers;

use Email_Campaign\AutomationGroups;
use Email_Campaign\AutomationGroup;
use Email_Campaign\AutomationEmails;
use Email_Campaign\NylasAuthEmails;
use Email_Campaign\EmailTemplate;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Email_Campaign\Mail\SendAutomationMail;


class CronController extends Controller
{
    public function test_automation_video(){
        $group = AutomationGroup::get_enabled_single_automation_groups();
        if ( !empty($group)) {
            $email          = AutomationEmails::get_group_single_email($group->id);
            if (empty($email)) {
                AutomationGroup::where('id','=',$group->id)->update(['ready'=>1]);

            }else{
                $contact        = Contact::where(['id'=>$email['receiver_id']])->get()->first()->toArray();
                $video_template = $group->video_template;
                $snapshots      = Template_snapshot::where(['template_id'=>$video_template])->get()->toArray();
                if(!empty($snapshots)){
                    $photos     = Template_photo::where(['template_id'=>$video_template])->get()->toArray();
                    $videos     = Template_video::where(['template_id'=>$video_template])->get()->toArray();
                    $urls       = Template_url::where(['template_id'=>$video_template])->get()->toArray();
                    $template   = Template::where(['id'=>$video_template])->get()->first();
                    $template   = ($template) ? $template->toArray() : null;

                    $final_sqs  = [];

                    if($template){
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
                                $row['snapshot_ss']  = base64_to_jpeg(explode(',',$contact['website_ss'])[1],'template/snapshot/');
                            }else if($row['snapshot']=='Facebook' && !empty($contact['facebook_ss'])){
                                $row['snapshot_ss']  = base64_to_jpeg(explode(',',$contact['facebook_ss'])[1],'template/snapshot/');
                            }else if($row['snapshot']=='Linked In' && !empty($contact['linkedin_ss'])){
                                $row['snapshot_ss']  = base64_to_jpeg(explode(',',$contact['linkedin_ss'])[1],'template/snapshot/');
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
                        array_multisort(array_column($final_sqs, 'order'), SORT_ASC, $final_sqs);
                        $order = 0;
                        $sep_arr[$order] = [];
                        foreach ($final_sqs as $key => $value) {
                            if($value['content']=='video'){
                                $order++;
                                $sep_arr[$order][] = $value;
                                $order++;
                            }else{
                                $sep_arr[$order][] = $value;
                            }
                        }

                        if(count($final_sqs)>0){
                            foreach ($sep_arr as $key => $value) {
                                $mute = (@$value[0]['content']!='video') ? 1 : @$value[0]['mute'];
                                if(count($value)>0){
                                    $sep_video[] = make_video($value);
                                }
                            }
                        }

                        foreach ($sep_video as $key => $value) {
                            if(!empty($final_audio[$key]['audio'])){
                                $final[] = add_audio_to_video($value,@$final_audio[$key]['audio']);
                            }else{
                                $final[] = add_video_over_video($value,@$final_audio[$key]['video_recorded']);
                            }
                        } 
                        
                        $template['final_video'] = concat_all_vdos($final);

                        if(!empty($template['intro_audio'])){
                            $video2 = $template['final_video'];
                            $video1 = make_video_with_user_audio($video2,$template['intro_audio']);
                            $template['final_video']  =  join_two_videos_for_campaign($video1,$video2);
                        }else if(!empty($contact['audio'])){
                            $video2 = $template['final_video'];
                            $video1 = make_video_with_user_audio($video2,$contact['audio']);
                            $template['final_video']  =  join_two_videos_for_campaign($video1,$video2);
                        }

                        $returnData['final_video'] = $template['final_video'];
                        $returnData['thumbnail']   = capture_thumb($template['final_video']);
                        AutomationEmails::where(['id'=>$email['id']])->update([
                            'final_video' => $returnData['final_video'],
                            'thumbnail'   => $returnData['thumbnail'],
                            'ready'       => 1
                        ]);
                    }
                }else{
                    $final_vidoes = Pre_final_template_video::where(['template_id'=>$video_template])->get()->first()->toArray();
                    $returnData['thumbnail']   = capture_thumb($final_vidoes['video']);
                    AutomationEmails::where(['id'=>$email['id']])->update([
                            'thumbnail'   => $returnData['thumbnail'],
                            'ready'       => 1
                        ]);
                }
            }
            
        }else{
           // AutomationGroup::where(['group_id'=>$group->id])->upadte(['ready'=>1]);
        }
        echo '1';
    }// eo

    public function send_automation_email(){
        $i        = 0;
        $group    = AutomationGroup::get_ready_group();
        if (!empty($group)) {
            $allEmail = AutomationEmails::where(['group_id'=>$group->id,'status'=>0])->get()->toArray();
            $group_id           = $group->id;
            $main_group         = $group->group_id;
            $contactGroup       = $group->receiver_id;
            $subject            = $group->email_subject;
            $video_template_id  = $group->video_template;
            $email_template_id  = $group->email_template;
            $email_send_at      = $group->send_at;
            $sendTime           = strtotime($email_send_at);
            $currentTime        = strtotime(date('Y-m-d H:i:s'));
            $contact_row        = Contact::where(['id'=>$group->receiver_id])->get()->first()->toArray();
            $fromData           = NylasAuthEmails::where(['user_id'=>$group->sender_id,'set_default'=>'1'])->get()->first()->toArray();
            $htmlData           = EmailTemplate::get_single_email_templates_without_user($email_template_id);
            $message            = $htmlData->email_html;
            $countEmails        = count($allEmail);

            if ( $currentTime >= $sendTime) {

                foreach ($allEmail as $key => $automationUser) {
                    $i++;
                    $data['thumb']          = $automationUser['thumbnail'];
                    $data['message_id']     = $automationUser['id'];
                    $data['html']           = $message;
                    
                    if ($automationUser['final_video'] == '') {
                        $final_vidoes = Pre_final_template_video::where(['template_id'=>$group->video_template])->get()->first()->toArray();
                        $data['final_video']    = $final_vidoes['video'];
                    }else{
                        $data['final_video']    = $automationUser['final_video'];
                    }
                    $body     = $this->replace_html($data);
                    $name     = $contact_row['first_name'].' '.$contact_row['last_name'];
                    $to       = $contact_row['email'];
                    $token    = $fromData['access_token'];
                    $response = $this->send_message($subject,$body,$name,$to,$token);

                    AutomationEmails::where('id','=',$automationUser['id'])->update(['status'=>1]);

                    //on last
                    if ($countEmails == $i) {
                        AutomationGroup::where('id','=',$group_id)->update(['status'=>2]);
                        $nextGroup  = AutomationGroup::get_single_group($main_group);
                        if (!empty($nextGroup)) {
                            $minutes                = $nextGroup->waitTime;
                            if ( $nextGroup->waitTime   != '') {
                                $calculatedDate     = date('Y-m-d ', strtotime(date('Y-m-d H:i:s') . $nextGroup->waitDate));
                                $time  = sprintf("%02d:%02d", floor($minutes / 60), ($minutes -   floor($minutes / 60) * 60));
                                $calculatedDateTime = $calculatedDate.' '.$time.':00';
                            }else{
                                $calculatedDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . $nextGroup->waitDate));
                            }
                            AutomationGroup::where('id','=',$nextGroup->id)->update([
                                'send_at'=> $calculatedDateTime,
                                'status' => 1
                            ]);
                        }else{
                            $restOfGroups = count(AutomationGroup::get_all_groups($main_group));
                            if($restOfGroups == 0){
                                AutomationGroups::where('id','=',$main_group)->update([
                                    'status' => 2
                                ]);
                            }
                        }
                    }//eo
                }
            }//eo current
        }//eo group
        echo "1";
    }
    public function create_automation_video(){
        $groups = AutomationGroup::get_enabled_automation_groups();

        foreach ($groups as $key => $value) {
            $i                  = 0;
            $nextGroup          = array();
        	$group_id           = $value->id;
            $main_group         = $value->group_id;
            $contactGroup 		= $value->receiver_id;
            $subject  		    = $value->email_subject;
            $video_template_id	= $value->video_template;
            $email_template_id	= $value->email_template;
            $email_send_at      = $value->send_at;
            $sendTime           = strtotime($email_send_at);
            $currentTime        = strtotime(date('Y-m-d H:i:s'));
            $fromData           = NylasAuthEmails::where(['user_id'=>$value->sender_id,'set_default'=>'1'])->get()->first()->toArray();
            $htmlData           = EmailTemplate::get_single_email_templates_without_user($email_template_id);
            $message            = $htmlData->email_html;

            $getAllEmail        = AutomationEmails::where(['group_id'=>$group_id,'status'=>0])->get()->toArray(); 
            $countEmails        = count($getAllEmail);

            if ( $currentTime >= $sendTime) {
                ##-----------------------------if time match -------------------------
                foreach ($getAllEmail as $key => $automationUser) {
                    $i++;
                    $contact_row    = Contact::where(['id'=>$automationUser['receiver_id']])->get()->first()->toArray();
                    // if ($automationUser['final_video'] == '') {

                    //     $videoData        = ['email'=>$contact_row['email'],'message_id'=>$automationUser['id'],'template'=>Template::get_template_detail($video_template_id),'type'=>'onetime','html'=>$message];
                    //     $returnData  = $this->genrate_video($videoData);

                    //     $data['thumb']          = $returnData['thumbnail'];
                    //     $data['final_video']    = $returnData['final_video'];
                    //     $data['message_id']     = $automationUser['id'];
                    //     $data['html']           = $message;
                    // }else{
                    //     $data['thumb']          = $automationUser['thumbnail'];
                    //     $data['final_video']    = $automationUser['final_video'];
                    //     $data['message_id']     = $automationUser['id'];
                    //     $data['html']           = $message;
                    // }
                    //$body           = $this->replace_html($data);
                    $body           = $message;
                    $name           = $contact_row['first_name'].' '.$contact_row['last_name'];
                    $to             = $contact_row['email'];
                    $access_token   = $fromData['access_token'];
                    $response       = $this->send_message($subject,$body,$name,$to,$access_token);
                    AutomationEmails::where('id','=',$automationUser['id'])->update(['status'=>1]);

                    //on last
                    if ($countEmails == $i) {
                        AutomationGroup::where('id','=',$group_id)->update(['status'=>2]);
                        $nextGroup  = AutomationGroup::where(['group_id'=>$main_group,'status'=>0])->get()->first();
                        if (!empty($nextGroup)) {
                            $minutes                = $nextGroup['waitTime'];
                            if ( $nextGroup['waitTime']   != '') {
                                $calculatedDate     = date('Y-m-d ', strtotime(date('Y-m-d H:i:s') . $nextGroup['waitDate']));
                                $time  = sprintf("%02d:%02d", floor($minutes / 60), ($minutes -   floor($minutes / 60) * 60));
                                $calculatedDateTime = $calculatedDate.' '.$time.':00';
                            }else{
                                $calculatedDateTime = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . $nextGroup['waitDate']));
                            }
                            AutomationGroup::where('id','=',$nextGroup['id'])->update([
                                'send_at'=> $calculatedDateTime,
                                'status' => 1
                            ]);
                        }else{
                            $restOfGroups = count(AutomationGroup::get_all_groups($main_group));
                            if($restOfGroups == 0){
                                AutomationGroups::where('id','=',$main_group)->update([
                                    'status' => 2
                                ]);
                                //break;
                            }
                        }
                    }//eo
                }  //break; 
                ##---------------------------eo
            }else{
                ##-----------------------------if time does not match --------------------------
                // foreach ($getAllEmail as $key => $automationUser) {
                //     if ($automationUser['final_video'] == '') {
                //         // echo $key;
                //         $contact_row    = Contact::where(['id'=>$automationUser['receiver_id']])->get()->first()->toArray();
                //         $data           = ['email'=>$contact_row['email'],'message_id'=>$automationUser['id'],'template'=>Template::get_template_detail($video_template_id),'type'=>'beforeTime','html'=>$message];
                //         $this->genrate_video($data);
                //         break;
                //     }

                // } 
                ##---------------------------eo
            }
            //break;
        }// eo for
    echo 1;
    }//eo function 


    public function genrate_video($data){
        $this->data         = $data;

        $id         = $this->data['message_id'];
        $history    = AutomationEmails::where(['id'=>$id])->get()->first()->toArray();
        $contact    = Contact::where(['id'=>$history['receiver_id']])->get()->first()->toArray();

        $vTemplate  = $this->data['template']->id;
        //$htmlTemp   = EmailTemplate::get_single_email_templates_without_user($vTemplate);
        $photos     = Template_photo::where(['template_id'=>$vTemplate])->get()->toArray();
        $videos     = Template_video::where(['template_id'=>$vTemplate])->get()->toArray();
        $urls       = Template_url::where(['template_id'=>$vTemplate])->get()->toArray();
        $snapshots  = Template_snapshot::where(['template_id'=>$vTemplate])->get()->toArray();
        $template   = Template::where(['id'=>$vTemplate])->get()->first();
        $template   = ($template) ? $template->toArray() : null;

        $final_sqs  = [];

        if($template){
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
                    $row['snapshot_ss']  = base64_to_jpeg(explode(',',$contact['website_ss'])[1],'template/snapshot/');
                }else if($row['snapshot']=='Facebook' && !empty($contact['facebook_ss'])){
                    $row['snapshot_ss']  = base64_to_jpeg(explode(',',$contact['facebook_ss'])[1],'template/snapshot/');
                }else if($row['snapshot']=='Linked In' && !empty($contact['linkedin_ss'])){
                    $row['snapshot_ss']  = base64_to_jpeg(explode(',',$contact['linkedin_ss'])[1],'template/snapshot/');
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
            array_multisort(array_column($final_sqs, 'order'), SORT_ASC, $final_sqs);

            $order = 0;
            $sep_arr[$order] = [];
            foreach ($final_sqs as $key => $value) {
                if($value['content']=='video'){
                    $order++;
                    $sep_arr[$order][] = $value;
                    $order++;
                }else{
                    $sep_arr[$order][] = $value;
                }
            }

            if(count($final_sqs)>0){
                foreach ($sep_arr as $key => $value) {
                    $mute = (@$value[0]['content']!='video') ? 1 : @$value[0]['mute'];
                    if(count($value)>0){
                        $sep_video[] = make_video($value);
                    }
                }
            }

            foreach ($sep_video as $key => $value) {
                if(!empty($final_audio[$key]['audio'])){
                    $final[] = add_audio_to_video($value,@$final_audio[$key]['audio']);
                }else{
                    $final[] = add_video_over_video($value,@$final_audio[$key]['video_recorded']);
                }
            } 
            
            $template['final_video'] = concat_all_vdos($final);

            if(!empty($template['intro_audio'])){
                $video2 = $template['final_video'];
                $video1 = make_video_with_user_audio($video2,$template['intro_audio']);
                $template['final_video']  =  join_two_videos_for_campaign($video1,$video2);
            }else if(!empty($contact['audio'])){
                $video2 = $template['final_video'];
                $video1 = make_video_with_user_audio($video2,$contact['audio']);
                $template['final_video']  =  join_two_videos_for_campaign($video1,$video2);
            }

            $returnData['final_video'] = $template['final_video'];
            $returnData['thumbnail']   = capture_thumb($template['final_video']);
            AutomationEmails::where(['id'=>$id])->update([
                'final_video' => $returnData['final_video'],
                'thumbnail'   => $returnData['thumbnail']
            ]);

            
        }
        if ($this->data['type'] = 'onetime') {
            return $returnData;
        }
        
    }
    public function replace_html($data){
        $this->data  = $data;
        $replaceHtml = '<img src="https://videoemailpro.com/'.$this->data['thumb'].' " style="max-width: 100%;">';
        $replaceHtml.= '<a href="https://videoemailpro.com/open_automation_video?id='.Crypt::encrypt($this->data['message_id']).'"><button class="playbtn" style="background-color: #1e88e5;border: none;width: 30%;padding: 10px;border-radius: 50px;color: white;font-weight: 600;position: relative;bottom: 150px;RIGHT: 5PX;margin: 20px;font-family: helvetica;box-shadow:1px 3px 7px #2f2f2f;font-size: 18px;">Watch Video</button></a>';
        $finalHtml        = preg_replace('/<p class=\"specifiedHooo\">.*<\/p>/',$replaceHtml,$this->data['html']);
        return $finalHtml;
    }
    public function send_message($subject,$body,$name,$to,$access_token){
        ##---------------------------send message ----------------------------
        $ch             = curl_init();

        $data =array (
              'subject' => $subject,
              'body' => $body,
              'to' => 
              array (
                0 => 
                array (
                  'name' => $name,
                  'email' => $to,
                ),
              ),
            );
        $payload = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch,CURLOPT_URL,"https://".$access_token.":@api.nylas.com/send");
        curl_setopt($ch,CURLOPT_POST ,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output  = curl_exec($ch);
        $err      = curl_error($ch);
        curl_close($ch);
        if ($err) {
          return false;
        } else {
          return true;
        } 
        #---------------------------------------------------------eo send email
    }

    public function genrate_html($data){
        // print_r($data['thumb']);
        // exit();
        $html = '';
        $html.='<!DOCTYPE html>';
        $html.='<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">';
        $html.='<head>';
        $html.='<meta charset="utf-8">';
        $html.='<meta name="viewport" content="width=device-width">';
        $html.='<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $html.='<meta name="x-apple-disable-message-reformatting">';
        $html.='<title></title>';
        $html.='<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">';
        $html.='<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">';
        $html.='</head>';
        $html.='not here';
        $html.='<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;">';
        $html.='<center style="width: 100%; background-color: #f1f1f1;">';
        $html.='<div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;</div>';
        $html.='<div class="gt" style="max-width: 600px;margin: 0 auto;background-color: #ffffff;">';
        $html.='<div style="max-width: 600px; margin: 0 auto; /* border: 2px solid #1e88e5;*/ "class="email-container">';
        $html.='<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;     background-color: white;">';
        $html.='<tr  style="background-color: white;">';
        $html.='<td valign="top" class="bg_white">';
        $html.='<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">';
        $html.='<tr>';
        $html.='<td class="logo" style=" text-align: center; padding-left: 10px;">';
        $html.='<h1><a href="#" style="color:#1e88e5; text-decoration: none;   font-family: poppins;font-weight: initial;">Email Campaign</a></h1>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<td valign="middle" class="bg_white" >';
        if($data['template']->final_video){
        $html.='<table style="margin-right: 0px !important;width: 100%; /* text-align: center; */">';
        $html.='<tr>';
        $html.='<td>';
        $html.='<div class="text" style=" text-align: center; margin-left: 10px; margin-right: 10px;">';
        $html.='<div class="play_thumb" style="/*background-color: #d3d3d35c;padding-bottom: 40px; padding-top: 44px; border-radius: 5px;"*/ >';
        $html.='<img src="'.URL($data['thumb']).'"style="width: 100%;">';
        $html.='</div>';
        $html.='<a href="'.route('open_automation_video',['id'=>Crypt::encrypt($data['message_id'])]).'">';
        $html.='<button class="playbtn" style="background-color: #1e88e5;border: none;width: 30%;padding: 10px;border-radius: 50px;color: white;font-weight: 600;position: relative;bottom: 150px;RIGHT: 5PX;margin: 20px;font-family: helvetica;box-shadow:1px 3px 7px #2f2f2f;font-size: 18px;">Watch Video</button>';
        $html.='</a>';
        $html.='<div class="tran" style="position:relative;">';
        $html.='<div class="triangle-up" style= "width: 0;height: 0;border-left: 25px solid transparent;border-right: 25px solid transparent;border-bottom: 50px solid #fff; position:absolute;bottom: 80px;right: 48%;"></div>';
        $html.='</div>';
        $html.='<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #eef5f9; border-radius: 10px; margin-top: -70px;">';
        $html.='<tr>';
        $html.='<td class="text-services" style="text-align: justify; padding: 20px;">';
        $html.='<div class="heading-section">                                                ';
        $html.='<p style="font-size: 22px;font-family: poppins; color:#868686;text-align: center;">';
        //$html.=$data;
        $html.='</p>';
        $html.='</div>';
        $html.='</td>';
        $html.='</tr>';
        $html.='<tr>';
        $html.='<td class="bg_light" style="text-align: center;">';
        $html.='<p style="font-size: 20px;padding: 20px;color: #868686;   font-family:poppins; margin-top: 0px;">Thank you</p>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</div>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        }
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='<tr style="BACKGROUND-COLOR:white;">';
        $html.='<td class="bg_white"></td>';
        $html.='</tr>';
        $html.='<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;background-color:white;padding-top: 30px;">';
        $html.='<tr>';
        $html.='<td class="bg_light" style="text-align: center; color: #585858;">';
        $html.='<i class="fa fa-facebook-square"></i> <i class="fa fa-instagram"></i> <i class="fa fa-twitter-square"></i>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; background-color:white;">';
        $html.='<tr>';
        $html.='<td class="bg_light" style="text-align: center;">';
        $html.='<p style="font-size: 15px;color: #8c8c8c;background-color: #ffffff;font-family:poppins;">© 2005-2011 Email Campaign All Rights Reserved</p>';
        $html.='</td>';
        $html.='</tr>';
        $html.='</table>';
        $html.='</div>';
        $html.='</div>';
        $html.='</center>';
        $html.='</body>';
        $html.='</html>';
        return $html;
    }//eo genrate html

}//eo class
