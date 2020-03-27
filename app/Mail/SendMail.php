<?php

namespace Email_Campaign\Mail;

use Email_Campaign\Group_member;
use Email_Campaign\Notification;
use Email_Campaign\Group;
use Email_Campaign\Template;
use Email_Campaign\Template_photo;
use Email_Campaign\Template_snapshot;
use Email_Campaign\Template_video;
use Email_Campaign\Pre_final_template_video;
use Email_Campaign\Contact;
use Email_Campaign\Template_url;
use Email_Campaign\History;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
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
        $name       = 'Email Campaign';
        $subject    = $this->subject;

        $id     = $this->data['message_id'];
        $history= History::where(['id'=>$id])->get()->first()->toArray();
        $contact= Contact::where(['id'=>$history['contact_id']])->get()->first()->toArray();

        $photos     = Template_photo::where(['template_id'=>$history['template_id']])->get()->toArray();
        $videos     = Template_video::where(['template_id'=>$history['template_id']])->get()->toArray();
        $urls       = Template_url::where(['template_id'=>$history['template_id']])->get()->toArray();
        $snapshots  = Template_snapshot::where(['template_id'=>$history['template_id']])->get()->toArray();
        $final_audio= Pre_final_template_video::where(['template_id'=>$history['template_id']])->get()->toArray();
        $template   = Template::where(['id'=>$history['template_id']])->get()->first();
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

            // if(!empty($template['outro_audio'])){
            //     $video1 = $template['final_video'];
            //     $video2 = $template['outro_audio'];
            //     $template['final_video']  =  join_two_videos_for_campaign($video1,$video2);
            // }

            History::where(['id'=>$id])->update([
                'video_link' => $template['final_video']
            ]);

            $this->data['thumb'] = capture_thumb($template['final_video']);
        }else{
            $this->data['template'] = json_decode(json_encode(['final_video'=>''],true));
        }
   
        return $this->view('emails.'.$this->view)->with($this->data)->from($address,$name)->subject($subject);
    }
}
