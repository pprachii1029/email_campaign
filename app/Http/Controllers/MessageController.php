<?php

namespace Email_Campaign\Http\Controllers;

use Illuminate\Http\Request;
use Email_Campaign\Group;
use Email_Campaign\Contact;
use Email_Campaign\History;
use Email_Campaign\Template;
use Email_Campaign\Group_member;
use Email_Campaign\AutomationGroups;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Email_Campaign\Mail\SendMail;


class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function compose_message(Request $request){
        $input  = $request->all();
        $data['groups'] = Group::get_all_groups(Auth::user()->id);
        $id =  !empty(@$input['id']) ? Crypt::decrypt($input['id']) : '';

        if(count($data['groups'])>0){
            $id = (!empty($id)) ? $id : $data['groups'][0]->id;
            $data['members']    = Contact::get_contacts_by_group_id_new($id);
            $data['selected']   = Group::get_group_details($id);
        }else{
            $data['members']    = [];
            $data['selected']   = [];
        }
        return view('compose_message')->with($data);
    }

    public function send_email_to_contact(Request $request){
        $userid     = Auth::user()->id;
        $input      = $request->all();
        $contacts   = json_decode($input['contact'],true);
        $subject    = $input['subject'];
        $message    = $input['message'];
        $template   = $input['template'];
        $thread     = time().$userid;

        foreach($contacts as $contact){
        	if($contact['unsubscribed']==0){
                $contact_id = Crypt::decrypt($contact['id']);
                $contact_row= Contact::where(['id'=>$contact_id])->get()->first()->toArray();

                #-- string replace with tags  --#
                $subject = str_replace('#firstName', $contact_row['first_name'], $subject);
                $subject = str_replace('#lastName', $contact_row['last_name'], $subject);
                $subject = str_replace('#email', $contact_row['email'], $subject);
                $subject = str_replace('#designation', $contact_row['designation'], $subject);
                $subject = str_replace('#phoneNumber', $contact_row['phone_number'], $subject);
                $subject = str_replace('#website', $contact_row['website'], $subject);
                $subject = str_replace('#facebook', $contact_row['facebook'], $subject);
                $subject = str_replace('#linkedin', $contact_row['linkedin'], $subject);
                #-- End str replacement --#

                #-- string replace with tags  --#
                $message = str_replace('#firstName', $contact_row['first_name'], $message);
                $message = str_replace('#lastName', $contact_row['last_name'], $message);
                $message = str_replace('#email', $contact_row['email'], $message);
                $message = str_replace('#designation', $contact_row['designation'], $message);
                $message = str_replace('#phoneNumber', $contact_row['phone_number'], $message);
                $message = str_replace('#website', $contact_row['website'], $message);
                $message = str_replace('#facebook', $contact_row['facebook'], $message);
                $message = str_replace('#linkedin', $contact_row['linkedin'], $message);
                #-- End str replacement --#

                $insert     = ['message'=>$message,'subject'=>$subject,'user_id'=>$userid,'contact_id'=>$contact_id,'group_id'=>0,'template_id'=>$template,'thread'=>$thread];
                $message_id = History::create($insert)->id;
                
	            Mail::to($contact['email'])->send(new SendMail('message',['name'=>$contact['name'],'message_id'=>$message_id,'data'=>$message,'template'=>Template::get_template_detail($template)], $subject,$contact_id));
	        }
        }
        return redirect()->route('sent_messages');
    }

    public function sent_messages(){
        $userid         = Auth::user()->id;
        $history        = History::get_sent_messages($userid);
        $data['message']= $history['data'];
        unset($history['data']);
        $data['pages']  = $history;

        return view('sent_messages')->with($data);
    }

    public function sent_campaigns(){
        $userid             = Auth::user()->id;
        $automation_groups  = AutomationGroups::get_automation_groups($userid);
        $history            = History::get_sent_campaigns($userid);
        $history_data       = $history['data'];
        $automation_data    = array();

        foreach ($history_data as $his) {

            $thread             =  $his->thread;
            $his->total_users   =  History::get_total_sentusers($thread);
            $his->sent_users    =  History::get_sent_sentusers($thread);
        }
        //history
        $data['campaigns']  = $history['data'];
        unset($history['data']);
        $data['pages']  = $history;

        //groups
        $data['groups']         =  $automation_groups['data'];
        unset($automation_groups['data']);
        $data['groups_pages']   = $automation_groups;
        // echo "<pre>";
        // print_r($automation_groups);
        // exit();
        return view('sent_campaigns')->with($data);
    }

    public function create_campaign(){
        $userid             = Auth::user()->id;
        $data['groups']     = Group::get_all_groups($userid);
        $data['templates']  = Template::get_all_templates($userid);
        return view('create_campaign')->with($data);
    }

    public function send_email_in_group(Request $request){
        $userid         = Auth::user()->id;
        $input          = $request->all();
        $subject        = $input['subject'];
        $message        = $input['message'];
        $template       = $input['template_id'];
        $group_members  = Contact::get_contacts_by_group_id($input['group_id']);
        $thread         = time().$userid;

        foreach($group_members as $contact){
        	
        	if($contact->unsubscribed==0){
	        	// Just temprary check need to change 
	        	$sup = DB::table('suppressions')->where('user_id','=',$userid)->where('group_id','=',$input['group_id'])->get()->toArray();
	        	$condit = true;
	        	foreach ($sup as $value) {
	        		$ch =  DB::table('contacts')->where('email','LIKE','%'.$value->host_name.'%')->where('id','=',$contact->id)->get()->toArray();
	        		if(count($ch)){
	        			$condit = false;
	        		}
	        	}

	        	if($condit){
                    $message1   = $message;  
                    $subject1   = $subject;
                    $contact_row= Contact::where(['id'=>$contact->id])->get()->first()->toArray();

                    #-- string replace with tags  --#
                    $subject1 = str_replace('#firstName', $contact_row['first_name'], $subject1);
                    $subject1 = str_replace('#lastName', $contact_row['last_name'], $subject1);
                    $subject1 = str_replace('#email', $contact_row['email'], $subject1);
                    $subject1 = str_replace('#designation', $contact_row['designation'], $subject1);
                    $subject1 = str_replace('#phoneNumber', $contact_row['phone_number'], $subject1);
                    $subject1 = str_replace('#website', $contact_row['website'], $subject1);
                    $subject1 = str_replace('#facebook', $contact_row['facebook'], $subject1);
                    $subject1 = str_replace('#linkedin', $contact_row['linkedin'], $subject1);
                    #-- End str replacement --#

                    #-- string replace with tags  --#
                    $message1 = str_replace('#firstName', $contact_row['first_name'], $message1);
                    $message1 = str_replace('#lastName', $contact_row['last_name'], $message1);
                    $message1 = str_replace('#email', $contact_row['email'], $message1);
                    $message1 = str_replace('#designation', $contact_row['designation'], $message1);
                    $message1 = str_replace('#phoneNumber', $contact_row['phone_number'], $message1);
                    $message1 = str_replace('#website', $contact_row['website'], $message1);
                    $message1 = str_replace('#facebook', $contact_row['facebook'], $message1);
                    $message1 = str_replace('#linkedin', $contact_row['linkedin'], $message1);
                    #-- End str replacement --#
                    $insert = ['message'=>$message1,'subject'=>$subject1,'user_id'=>$userid,'contact_id'=>$contact->id,'group_id'=>$input['group_id'],'template_id'=>$template,'thread'=>$thread];
                    $message_id = History::create($insert)->id;

		          // Mail::to($contact->email)->send(new SendMail('message',['name'=>$contact->first_name.' '.$contact->last_name,'message_id'=>$message_id,'data'=>$message1,'template'=>Template::get_template_detail($template)], $subject1,$contact->id)); 
		        }
		    }
        }
        return redirect()->route('sent_campaigns');
    }


    public function send_template_email(Request $request){
        $userid     = Auth::user()->id;
        $history    = DB::table('histories')->where('sent_status','=',0)->limit(1)->get()->toArray();

        foreach($history as $hist){
            $message_id      = $hist->id;

            DB::table('histories')->where('id', $message_id)->update([
            'sent_status' => 1
            ]);
        }

        foreach($history as $hist){
            $subject1        = $hist->subject;
            $message1        = $hist->message;
            $template        = $hist->template_id;

            $contact_id      = $hist->contact_id;
            $message_id      = $hist->id;

            $contact_row = Contact::where(['id'=>$contact_id])->get()->first()->toArray();

            #-- string replace with tags  --#
            $subject1 = str_replace('#firstName', $contact_row['first_name'], $subject1);
            $subject1 = str_replace('#lastName', $contact_row['last_name'], $subject1);
            $subject1 = str_replace('#email', $contact_row['email'], $subject1);
            $subject1 = str_replace('#designation', $contact_row['designation'], $subject1);
            $subject1 = str_replace('#phoneNumber', $contact_row['phone_number'], $subject1);
            $subject1 = str_replace('#website', $contact_row['website'], $subject1);
            $subject1 = str_replace('#facebook', $contact_row['facebook'], $subject1);
            $subject1 = str_replace('#linkedin', $contact_row['linkedin'], $subject1);
            #-- End str replacement --#

            #-- string replace with tags  --#
            $message1 = str_replace('#firstName', $contact_row['first_name'], $message1);
            $message1 = str_replace('#lastName', $contact_row['last_name'], $message1);
            $message1 = str_replace('#email', $contact_row['email'], $message1);
            $message1 = str_replace('#designation', $contact_row['designation'], $message1);
            $message1 = str_replace('#phoneNumber', $contact_row['phone_number'], $message1);
            $message1 = str_replace('#website', $contact_row['website'], $message1);
            $message1 = str_replace('#facebook', $contact_row['facebook'], $message1);
            $message1 = str_replace('#linkedin', $contact_row['linkedin'], $message1);

            Mail::to($contact_row['email'])->send(new SendMail('message',['name'=>$contact_row['first_name'].' '.$contact_row['last_name'],'message_id'=>$message_id,'data'=>$message1,'template'=>Template::get_template_detail($template)], $subject1,$contact_id)); 
        }
        echo 1;
        
    }
    
}
