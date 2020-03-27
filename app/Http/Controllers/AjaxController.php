<?php

namespace Email_Campaign\Http\Controllers;

use Email_Campaign\AutomationGroups;
use Email_Campaign\AutomationGroup;
use Email_Campaign\Contact;
use Email_Campaign\Group;
use Email_Campaign\Group_member;
use Email_Campaign\Template;
use Email_Campaign\History;
use Email_Campaign\Notification;
use Email_Campaign\Suppression;
use Email_Campaign\EmailTemplate;
use Email_Campaign\NylasAuthEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AjaxController extends Controller
{
    public function view_contacts(Request $request){
        $id                 = Crypt::decrypt($request->all()['id']);
        $data['contacts']   = Contact::get_contacts_by_group_id($id);
        $data['group_name'] = Group::get_group_details($id)->group_name;
        return view('ajax/view_contacts')->with($data);
    }

    public function delete_contact(Request $request){
        $id = Crypt::decrypt($request->all()['id']);
        Contact::where('id','=',$id)->delete();
        echo 200;
    }

    public function get_template_detail(Request $request){
        $input  = $request->all();
        $id     = $input['id'];
        echo json_encode(Template::get_template_detail($id));
    }

    public function delete_template(Request $request){
        $input      = $request->all();
        $response   = array();
        $id         = Crypt::decrypt($input['id']);
        $data['video_template'] = $id;
        $data['status']     = 1;
        $response           = AutomationGroup::check_template_exist($data);
        if (empty($response)) {
            echo Template::delete_template($id);
        }else{
            echo "400";
        }
        
    }

    public function subscribe_unsubscribe(Request $request){
        $id         = Crypt::decrypt($request->all()['id']);
        $contact    = Contact::get_contact($id);
        if($contact->unsubscribed){
            Contact::where('id','=',$id)->update(['unsubscribed'=>0]);
        }else{
            Contact::where('id','=',$id)->update(['unsubscribed'=>1]);
        }
        echo 200;
    }

    public function capture_url(Request $request){
        $url          = addhttp($request->all()['url']);
        $screen_shot  = base64_encode(file_get_contents("http://api.screenshotlayer.com/api/capture?access_key=".env('SCREEN_SHOT_API_KEY')."&url=".$url."&viewport=1440x900&width=720"));
        if(strlen($screen_shot)>1000){
            echo  $screen_shot;
        }else{
            echo abc();
        }
    }

    public function append_video(Request $request){
        $data = $request->all();
        return view('ajax/append_vdo')->with($data);
    }

    public function append_url(Request $request){
        $data = $request->all();
        return view('ajax/append_url')->with($data);
    }

    public function append_photo(Request $request){
        $data = $request->all();
        return view('ajax/append_photo')->with($data);
    }

    public function append_snapshot(Request $request){
        $data = $request->all();
        return view('ajax/append_snapshot')->with($data);
    }
    
    public function upload_files(Request $request){
        $content = $request->all()['content'];
        $url     = upload($request,'file','template/'.$content);
        if($content=='video'){
            $return =  '<video width="100%" controls class="myvideo"><source src="'.URL($url).'" ></video>';
            $return = array('html'=>$return,'content'=>'video','url'=>$url);
        }else if($content=='photo'){
            $return = '<img src="'.URL($url).'" class="img-fluid">';
            $return = array('html'=>$return,'content'=>'photo','url'=>$url);
        }
        return json_encode($return);
    }

    public function upload_csv(Request $request){
        $url     = upload($request,'file','csv');
        $assoc_array = [];
        if (($handle = fopen($url,"r")) !== false){
            if (($data = fgetcsv($handle, 1000, ",")) !== false){
                $keys = $data;
            }
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $assoc_array[] = array_combine($keys, $data);
            }
            fclose($handle);
        }

        $groups = Group::get_all_groups(Auth::user()->id);
        return view('ajax/view_csv')->with(['data'=>$assoc_array,'groups'=>$groups]);
    }
    
    public function upload_capture_url(Request $request){
        $url = $request->all()['url'];
        $screen_shot_json_data  = file_get_contents("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$url&screenshot=true");
        $screen_shot_result     = json_decode($screen_shot_json_data, true);
        $screen_shot            = $screen_shot_result['screenshot']['data'];
        $screen_shot            = str_replace(array('_','-'), array('/', '+'), $screen_shot);
        $url                    = base64_to_jpeg($screen_shot,'template/url/');

        $return = '<img src="'.URL($url).'" class="pl-3" >';
        $return = array('html'=>$return,'content'=>'url','url'=>$url);
        return  json_encode($return);
    }

    public function update_video_view(Request $request){
        $arr = $request->all()['arr'];
        $html= "";
        $i = 0;
        foreach($arr as $row){
            $html .=  '<div class="col-md-3 draggable" id="'.$i++.'" url="'.$row['url'].'" content="'.$row['content'].'">'.$row['html'].'</div>';
        }

        return $html;
    }

    public function delete_suppression(Request $request){
        $input  = $request->all();
        $id     = Crypt::decrypt($input['id']);
        if(Suppression::where(['id'=>$id])->delete()){
            return 200;
        }else{
            return 500;
        }
    }

    public function upload_blob(Request $request){
        $url    = upload_blob($request,'file','audio');
        echo $url;
    }

    public function upload_blob_video(Request $request){
        $url    = upload_blob_video($request,'file','audio');
        echo $url;
    }

    public function move_contact(Request $request){
        $contact_id = $request->all()['contact_id'];
        $groups = Group::get_all_groups(Auth::user()->id);
        
        return view('ajax/move_contact')->with(['groups'=>$groups,'contact_id'=>$contact_id]);
    }

    public function change_group(Request $request){
        $contact_id = Crypt::decrypt($request->all()['contact_id']);
        $group_id   = Crypt::decrypt($request->all()['group_id']);
        $user_id    = Auth::user()->id;

        Group_member::where(['contact_id'=>$contact_id,'user_id'=>$user_id])->update(['group_id'=>$group_id]);   
        echo 200;
    }

    public function send_message_pop(Request $request){
        $userid = Auth::user()->id;
        $contact_id = Crypt::decrypt($request->all()['contact_id']);
        $data['templates']  = Template::get_all_templates($userid);
        $data['contact']    = Contact::get_contact($contact_id);
        return view('ajax/compose_message_pop')->with($data);
    }

    public function delete_camp(Request $request){
        $id = Crypt::decrypt($request->all()['id']);
        $user_id = Auth::user()->id;
        $thread = History::where(['id'=>$id])->get()->toArray()[0]['thread'];
        History::where(['thread'=>$thread,'user_id'=>$user_id])->delete();
        echo 200;
    }

    public function delete_message(Request $request){
        $id = Crypt::decrypt($request->all()['id']);
        History::where(['id'=>$id])->delete();
        echo 200;
    }

    public function preview_with_audio(Request $request){
        $input = $request->all();
        $vdo   = add_audio_to_video($input['video'],$input['audio']);
        echo $vdo;
    }

    public function update_video_play_timer(Request $request){
        $input = $request->all();
        Notification::where(['id'=>Crypt::decrypt($input['notification_id'])])->update(['time'=>gmdate('H:i:s', (int)$input['time'])]);
        echo 200;
    }
    public function save_email_html(Request $request){
        $input      = $request->all();
        $image      = $request->dataURL;  // your base64 encoded
        // print_r($image);
        // exit();
        $image      = str_replace('data:image/png;base64,', '', $image);
        $image      = str_replace(' ', '+', $image);
        $imageName  = 'preview_'.str_random(10) . '.png';
        $path       = public_path('images/email_template')."/".$imageName;
        $upload_path= '/public/images/email_template/'.$imageName;
        $val        = file_put_contents($path, base64_decode($image));
        $insert     = ['user_id'=>Auth::user()->id,'email_html'=>$request->html,'template_name'=>$request->name,'template_image'=>$upload_path];
        EmailTemplate::create($insert);
        echo 200;
    }

    public function get_email_templates(Request $request){
        $input  = $request->all();
        $data       = EmailTemplate::get_all_email_templates(Auth::user()->id);
        $email_data = array();
        foreach ($data['data'] as $key => $value) {
            $email_data[$key]['id']             = $value->id;
            $email_data[$key]['email_html']     = $value->email_html;
            $email_data[$key]['template_name']  = $value->template_name;
            $email_data[$key]['created_at']     = $value->created_at;
        }
        
        $email_data['email_data'] = $email_data;
        return view('email_template_list')->with($email_data);
    }
    public function get_single_email_templates(Request $request){
        $input      = $request->all();
        $view_id    = $request->view_id;
        $user_id    = Auth::user()->id;
        $data       = EmailTemplate::get_single_email_templates($view_id,$user_id);
        echo $data[0]->email_html;
    }

    public function delete_email_template(Request $request){
        $input              = $request->all();
        $template_id        = $request->template_id;
        $response           = array();
        $data['email_template'] = $template_id;
        $data['status']     = 1;
        $response           = AutomationGroup::check_template_exist($data);
        if ( empty($response)) {
            $user_id            = Auth::user()->id;
            $data               = EmailTemplate::delete_email_template($template_id,$user_id);
            echo "200";
        }else{
            echo "400";
        }
    }

    public function update_email_template(Request $request){
       $input       = $request->all(); 
       return view('update_email_template');
    }

    //nyals api
    public function nylas_verify_email(Request $request){
        $input                  = $request->all(); 
        $array['user_id']       = Auth::user()->id;
        $data                   = NylasAuthEmails::get_all_auth_email_data($array);
        $data['data']           = $data;
        return view('verify_email')->with($data);
    }
    public function oauth_authorize(Request $request){
        $input       = $request->all();
        $ch          = curl_init();
        // $header = array(
        //     'Accept: application/json',
        //     'Content-Type: application/x-www-form-urlencoded',
        //     'Authorization: Basic '. base64_encode("2wgl4tkwn4pswoprqjn2o83tz:2ld16xlrh46rhc3k57phszbyt")
        // );
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch,CURLOPT_URL,"https://api.nylas.com/oauth/authorize?client_id=6nrrs9wlpbin0zwbajgzg1344&redirect_uri=https://videoemailpro.com/oauth_token&response_type=token&scopes=email.send,email.read_only&login_hint=".$input['email']."&state=".$input['_token']." ");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output     = curl_exec($ch);
        curl_close($ch);
        $data['email']                   = $input['email'];
        $response_type['csrf_token']     = $input['_token'];
        $response = NylasAuthEmails::get_auth_email_data($data);

        echo  $output;
    }
    public function oauth_token(Request $request){
        $input                          = $request->all(); 
        if (!empty($input)) {
            $data['email']              = $input['email_address'];
            $data['user_id']            = Auth::user()->id;
            $checkParam                 = $data;
            $check                      = NylasAuthEmails::get_auth_email_data($checkParam);
            $data['access_token']       = $input['access_token'];
            $data['provider']           = $input['provider'];
            $data['account_id']         = $input['account_id'];

            $count['user_id']           = Auth::user()->id;
            $all_accounts               = array();
            $all_accounts               = NylasAuthEmails::get_auth_email_data($count);

            if ( !empty($all_accounts)) {
                $data['set_default']        = '0';
            }else{
                $data['set_default']        = '1';
            }
            
            $checkParam                 = array();
            $checkParam['email']        = $data['email'];
            $updateData['access_token'] = $data['access_token'];
            $updateData['account_id']   = $data['account_id'];

            
            if (!empty($check)) {
                $url    = "https://api.nylas.com/a/6nrrs9wlpbin0zwbajgzg1344/accounts/".$input['account_id']."/revoke-all"; 
                $ch     = curl_init();
                $header = array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic '. base64_encode("glp9vdibpzhue75m69axdi30:glp9vdibpzhue75m69axdi30")
                );
                $curlData =array ('keep_access_token' => $input['access_token'],);
                $payload = json_encode($curlData);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_POST ,1);

                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $response = curl_exec($ch);
                $err      = curl_error($ch);

                curl_close($ch);

                if ($err) {
                  NylasAuthEmails::insert($data);
                  NylasAuthEmails::update_auth_email($checkParam,$updateData);
                } else {
                  NylasAuthEmails::update_auth_email($checkParam,$updateData);
                }
                
            }else{
                NylasAuthEmails::insert($data);
                NylasAuthEmails::update_auth_email($checkParam,$updateData);
            }
        }
        return view('oauth_token');
    }
    public function revoke_session(Request $request){
        $input                          = $request->all(); 
        $account_id                     = Crypt::decrypt($input['id']);
        $data['user_id']                = Auth::user()->id;
        $check                          = NylasAuthEmails::get_all_auth_email_data($data);
        $total_connections              = count($check);

        $data['account_id']             = $account_id;
        $check_status                   = NylasAuthEmails::get_auth_email_data($data);
        $status                         = $check_status->set_default;
        if ($status !=1 ) {
            if($total_connections > 1){
                $url    = "https://api.nylas.com/a/6nrrs9wlpbin0zwbajgzg1344/accounts/".$account_id."/revoke-all"; 
                $ch     = curl_init();
                $header = array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Basic '. base64_encode("glp9vdibpzhue75m69axdi30:glp9vdibpzhue75m69axdi30")
                );
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_POST ,1);

                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $err      = curl_error($ch);
                curl_close($ch);

                echo NylasAuthEmails::where(['account_id'=>$account_id,'user_id'=>Auth::user()->id])->delete();

            }else{
                echo 400;
            }
        }else{
            echo 400;
        }
        
    }
    public function set_default(Request $request){
        $input                  = $request->all(); 
        $id                     = Crypt::decrypt($input['id']);
        $checkParam['user_id']  = Auth::user()->id;
        $data['set_default']    = 0;
        NylasAuthEmails::update_auth_email($checkParam,$data);
        $checkParam             = array();
        $checkParam['id']       = $id;
        $data['set_default']    = 1;
        echo NylasAuthEmails::update_auth_email($checkParam,$data);
    }

}
// &response_type=token&redirect_uri=MY_REDIRECT_URI&scopes=email.send,email.read_only&state=CSRF_TOKEN