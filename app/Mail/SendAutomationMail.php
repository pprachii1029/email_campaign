<?php

namespace Email_Campaign\Mail;

use Email_Campaign\AutomationEmails;
use Email_Campaign\NylasAuthEmails;
use Email_Campaign\Group_member;
use Email_Campaign\Notification;
use Email_Campaign\Group;
use Email_Campaign\Template;
use Email_Campaign\Template_photo;
use Email_Campaign\Template_snapshot;
use Email_Campaign\Template_video;
use Email_Campaign\Contact;
use Email_Campaign\Template_url;
use Email_Campaign\History;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAutomationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view,$data,$subject,$contact_id=0)
    {
        $this->view         = $view;
        $this->data         = $data;
        $this->subject      = $subject;
        $this->contact_id   = $contact_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address    = 'noreply@email_campaign.com';
        $name       = 'Automation Email Campaign';
        $subject    = $this->subject;

        $id         = $this->data['message_id'];
        $history    = AutomationEmails::where(['id'=>$id])->get()->first()->toArray();
        $contact    = Contact::where(['id'=>$history['receiver_id']])->get()->first()->toArray();
        $fromData   = NylasAuthEmails::where(['user_id'=>$history['sender_id']])->get()->first()->toArray();
        if( !empty($fromData)){
            if ($fromData['email'] != '' ) {
                $address    = $fromData['email'];
            }
        }
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
            $order = array_column($final_sqs, 'order');
            array_multisort($order, SORT_ASC, $final_sqs);

            if(count($final_sqs)>0){
                $final_video                = make_video($final_sqs);
                $final_video                = add_audio_to_video($final_video,$template['audio']);
                $template['final_video']    = $final_video;
            }

            if(!empty($template['intro_audio'])){
                $video2 = $template['final_video'];
                $video1 = make_video_with_user_audio($video2,$template['intro_audio']);
                $template['final_video']    =  join_two_videos_for_campaign($video1,$video2);
            }else if(!empty($contact['audio'])){
                $video2 = $template['final_video'];
                $video1 = make_video_with_user_audio($video2,$contact['audio']);
                $template['final_video']    =  join_two_videos_for_campaign($video1,$video2);
            }

            if(!empty($template['outro_audio'])){
                $video1 = $template['final_video'];
                $video2 = $template['outro_audio'];
                $template['final_video']    =  join_two_videos_for_campaign($video1,$video2);
            }

            AutomationEmails::where(['id'=>$id])->update([
                'final_video' => $template['final_video']
            ]);

            $this->data['thumb']    = capture_thumb($template['final_video']);
            //$this->data['html']     = '';
            $html                   = $this->data['html'];
            if ( $html != '') {
                $replaceHtml            = '<img src="'.URL($this->data['thumb']).' " style="max-width: 100%;">';
                $replaceHtml.= '<a href="'.route('open_automation_video',['id'=>Crypt::encrypt($this->data['message_id'])]).'"><button class="playbtn" style="background-color: #1e88e5;border: none;width: 30%;padding: 10px;border-radius: 50px;color: white;font-weight: 600;position: relative;bottom: 150px;RIGHT: 5PX;margin: 20px;font-family: helvetica;box-shadow:1px 3px 7px #2f2f2f;font-size: 18px;">Watch Video</button></a>';
                $this->data['html']    = preg_replace('/<p class=\"specifiedHooo\">.*<\/p>/',$replaceHtml,$html);
            } 
            
        }else{
            $this->data['template'] = json_decode(json_encode(['final_video'=>''],true));
        }

        return $this->view('emails.'.$this->view)->with($this->data);
        //send email
        // $subject        = $subject; 
        // $body           = $this->data['html']; 
        // $to             = $this->data['email'];  
        // $access_token   = 'drctOJUKjFbj8ParmkHBsO7rd08sRj';
        // $ch             = curl_init();

        // $data =array (
        //       'subject' => $subject,
        //       'body' => $body,
        //       'to' => 
        //       array (
        //         0 => 
        //         array (
        //           'name' => 'My Nylas Friend',
        //           'email' => $to,
        //         ),
        //       ),
        //     );
        // $payload = json_encode($data);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt($ch,CURLOPT_URL,"https://".$access_token.":@api.nylas.com/send");
        // curl_setopt($ch,CURLOPT_POST ,1);
        // curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        // $output  = curl_exec($ch);
        // $err      = curl_error($ch);
        // curl_close($ch);
        // if ($err) {
        //   return false;
        // } else {
        //   return true;
        // } 
        //eo send email

    }//eo function
}// eo class



