<?php

namespace Email_Campaign\Http\Controllers;
use Email_Campaign\AutomationGroups;
use Email_Campaign\AutomationGroup;
use Email_Campaign\AutomationEmails;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AutomationEmailController extends Controller
{

    public function edit_email_drip( Request $request){
        $input                            = $request->all();
        $template_id                      = Crypt::decrypt($input['template']);
        $main_group                       = AutomationGroups::get_automation_group_details($template_id);
        $automation                       = AutomationGroup::get_automation_group($template_id);
        $pagination                       = 1000;
        $email_template                   = EmailTemplate::get_all_email_templates(Auth::user()->id,$pagination);
        $group_data                       = Group::get_group_which_have_members(Auth::user()->id);
        $video_template                   = Template::get_all_templates_table(Auth::user()->id,$pagination);

        foreach ($email_template['data'] as $key => $value) {
            $eData[$value->id]['template_image'] = $value->template_image;
            $eData[$value->id]['template_name'] = $value->template_name;
        }
        foreach ($video_template['data'] as $key => $value) {
            $vData[$value['id']]['template_image'] = $value['final_video'];
            $vData[$value['id']]['template_name']  = $value['title'];
        }

        foreach ($automation['data'] as $key => $value) {
            $timingObject = explode(" ", $value->waitDate);
            if( $value->waitTime !=''){
                if( floor($value->waitTime / 60) > 0){
                        $time  = sprintf("%02d:%02d", floor($value->waitTime / 60), ($value->waitTime -   floor($value->waitTime / 60) * 60));
                        $display_time = $time;
                }else{
                        $time  = sprintf("%02d:%02d", 12, ($value->waitTime -   floor($value->waitTime / 60) * 60));
                        $display_time = $time;
                }
            }else{
                $display_time = '';
            }
            $data['selected_contact']                          = $value->receiver_id;
            $automation['data'][$key]->wait                    = $timingObject[0];
            $automation['data'][$key]->object                  = $timingObject[1];
            $automation['data'][$key]->display_time            = $display_time;
            $automation['data'][$key]->email_template_name     = $eData[$value->email_template]['template_name'];
            $automation['data'][$key]->email_template_image    = $eData[$value->email_template]['template_image'];
            $automation['data'][$key]->video_template_name     = $vData[$value->video_template]['template_name'];
            $automation['data'][$key]->video_template_image    = $vData[$value->video_template]['template_image'];
        }
        $data['automation_id']            = $main_group->id;
        $data['automation_name']          = $main_group->automation_name;
        $data['status']                   = $main_group->status;
        $data['email_template']           = $email_template['data']; 
        $data['video_template']           = $video_template['data']; 
        $data['contacts']                 = $group_data;   
        $data['template_group']           = $automation['data'];
        $data['total_data']               = count($automation['data']);
        unset($automation['data']);
        $data['pagination']               = $automation;
         return view('edit_email_drip')->with($data);

    }
    
 
    public function create_automation_email(Request $request){
        $input      = $request->all();
        $server_timeZone  = date_default_timezone_get();
        $local_timeZone   = '';
        $local_datetime   = $input['sending_date'];
        // $timezone         = $input['timezone'];
        $insert     = ['sender_id'=>Auth::user()->id,'receiver_id'=>$input['receiver_group'],'email_subject'=>$input['email_subject'],'email_template_id'=>$input['email_template'],'send_date'=>$input['sending_date'],'status'=>'0' ];
        AutomationEmails::create($insert);
        echo 200;
    }
    public function re_save_automation(Request $request ){
    	$input                          = $request->all();
    	
        $id                      		= Crypt::decrypt($input['id']);
        $template_name                  = $input['template_name'];
        $email_templates                = explode(',',$input['eTmp']);
        $video_templates                = explode(',',$input['video_templates']);
        $wait                      		= explode(',',$input['wait']);
        $object                      	= explode(',',$input['object']);
        $waitTime                      	= explode(',',$input['waitTime']);
        $contact                        = $input['contact'];

        $automationEmails = DB::table('automation_group')->select('id')->where('group_id','=',$id)->get()->toArray();
        foreach ($automationEmails as $key => $value) { $group[$key] = $automationEmails[$key]->id; }
        if (!empty($group)) {   DB::table('automation_emails')->whereIn('group_id', $group)->delete();  }
       	DB::table('automation_group')->where('group_id','=', $id)->delete();
       	DB::table('automation_groups')->where('id', $id)->update(['automation_name' => $template_name]);

       	$this->get_save_automation($contact,$id,$template_name,$email_templates,$video_templates,$wait,$object,$waitTime);
        
        echo 200;

        
    }
    public function save_and_start(Request $request ){
    	$input                          = $request->all();
        $id                      		= Crypt::decrypt($input['id']);
        $template_name                  = $input['template_name'];
        $email_templates                = explode(',',$input['eTmp']);
        $video_templates                = explode(',',$input['video_templates']);
        $wait                      		= explode(',',$input['wait']);
        $object                      	= explode(',',$input['object']);
        $waitTime                      	= explode(',',$input['waitTime']);
        $contact                        = $input['contact'];

        $automationEmails = DB::table('automation_group')->select('id')->where('group_id','=',$id)->get()->toArray();
        foreach ($automationEmails as $key => $value) { $group[$key] = $automationEmails[$key]->id; }
        if (!empty($group)) {   DB::table('automation_emails')->whereIn('group_id', $group)->delete();  }
       	DB::table('automation_group')->where('group_id','=', $id)->delete();
       	DB::table('automation_groups')->where('id', $id)->update(['automation_name' => $template_name]);

       	$this->get_save_automation($contact,$id,$template_name,$email_templates,$video_templates,$wait,$object,$waitTime);
       	$this->get_start_automation($id);
        
        echo 200;

        
    }
    public function create_automation_groups( Request $request){
        $input                        = $request->all();
        $email_templates              = explode(',',$input['email_templates']);
        $video_templates              = explode(',',$input['video_templates']);
        $wait                         = explode(',',$input['wait']);
        $object                       = explode(',',$input['object']);
        $waitTime                     = explode(',',$input['waitTime']);
        $contact                      = $input['contact'];
        $start                        = $input['start'];
        $automation_name        	  = $input['template_name'];

        $insert                 = ['sender_id'=>Auth::user()->id,'automation_name'=> $automation_name,'status'=>'0'];
        AutomationGroups::create($insert);
        $groups_id              = DB::getPdo()->lastInsertId();
        $this->get_save_automation($contact,$groups_id,$automation_name,$email_templates,$video_templates,$wait,$object,$waitTime);
	        if ($start != 0) {
	            $this->get_start_automation($groups_id);
	        }
        echo 200;
        
    }

    public function start_automation( Request $request ){
        $input                  = $request->all();
        $main_group_id          = Crypt::decrypt($input['id']);
        $resposne = $this->get_start_automation($main_group_id);
           
        echo 200;
    }
    public function pause_automation(Request $request){
        $input                  = $request->all();
        $main_group_id          = Crypt::decrypt($input['id']);
        $resposne = $this->get_pause_automation($main_group_id);
           
        echo 200;
    }
    public function delete_whole_automation(Request $request){
        $input                  = $request->all();
        $id          = Crypt::decrypt($input['id']);
        $automationEmails = DB::table('automation_group')->select('id')->where('group_id','=',$id)->get()->toArray();
        foreach ($automationEmails as $key => $value) { $group[$key] = $automationEmails[$key]->id; }
        if (!empty($group)) {   DB::table('automation_emails')->whereIn('group_id', $group)->delete();  }
        DB::table('automation_group')->where('group_id','=', $id)->delete();
        DB::table('automation_groups')->where('id','=', $id)->delete();

        echo 200;
    }
    // recall function
    public function get_save_automation($contact,$groups_id,$automation_name,$email_templates,$video_templates,$wait,$object,$waitTime){
    	for ($i=0; $i <count($email_templates) ; $i++) { 
       		$input['data'][$i]['sender_id']      = Auth::user()->id;
       		$input['data'][$i]['receiver_id']    = $contact;
       		$input['data'][$i]['group_id'] 		 = $groups_id;
       		$input['data'][$i]['email_subject']  = $automation_name;
            $input['data'][$i]['email_template'] = $email_templates[$i];
            $input['data'][$i]['video_template'] = $video_templates[$i];
            $input['data'][$i]['waitDate']       = $wait[$i].' '.$object[$i];
            if ($waitTime[$i] == 'n') {
                $input['data'][$i]['waitTime']       = '';
            }else{
                $input['data'][$i]['waitTime']       = $waitTime[$i];
            }
        }//eofor
        AutomationGroup::insert($input['data']);
        
    }
    public function get_start_automation( $main_group_id){
        $subGroupByUsers        = array();
        $automationAllGroups    = DB::table('automation_group')->select(DB::raw('*'))->where('group_id','=',$main_group_id)->get()->toArray();
        $index                  = 0;
        $subGroupByUsers        = array();
        
        foreach ($automationAllGroups as $key => $data) {

            if ( $key == 0) { $initial_drip = $data->id;}
        	$minutes                = $data->waitTime;
        	if ( $data->waitTime   != '') {
                $calculatedDate     = date('Y-m-d ', strtotime(date('Y-m-d H:i:s') . $data->waitDate));
                $time  = sprintf("%02d:%02d", floor($minutes / 60), ($minutes -   floor($minutes / 60) * 60));
                $calculatedDateTime[$key] = $calculatedDate.' '.$time.':00';
            }else{
                $datete =  strtotime("+".$data->waitDate, strtotime(date('Y-m-d H:i:s')));
                $calculatedDateTime[$key] = date('Y-m-d H:i:s', $datete);

            }

        	$group_members  = Contact::get_contacts_by_group_id($data->receiver_id); 
        	foreach ($group_members as $key => $members) {
                $subGroupByUsers[$index]['group_id']        = $data->id;
                $subGroupByUsers[$index]['sender_id']       = $data->sender_id;
                $subGroupByUsers[$index]['receiver_id']     = $members->id;
                $subGroupByUsers[$index]['final_video']     = '';
                $index++;
            }
        }

        AutomationEmails::insert($subGroupByUsers);
        DB::table('automation_groups')->where('id', $main_group_id)->update([
        'status' => 1
        ]);

        DB::table('automation_group')->where(['id'=>$initial_drip],['status'=>0])->update([
        'send_at'=> $calculatedDateTime[0],
        'status' => 1
        ]);

        
    }//eo
    public function get_pause_automation($main_group_id){
        DB::table('automation_groups')->where('id', $main_group_id)->update([
        'status' => 0
        ]);

        DB::table('automation_group')->where(['group_id'=>$main_group_id],['status'=>0])->update([
        'status' => 0
        ]);
    }
}
