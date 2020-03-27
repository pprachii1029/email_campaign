<?php

namespace Email_Campaign\Http\Controllers;

use Illuminate\Http\Request;
use Email_Campaign\Group;
use Email_Campaign\Contact;
use Email_Campaign\Group_member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ContactController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    public function index(Request $request)
    {
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

        return view('index')->with($data);       
    }

    public function add_contact_view(){
        $data['groups'] = Group::get_all_groups(Auth::user()->id);
        return view('add_contact')->with($data);
    }

    public function add_contact(Request $request){
        $user_id    = Auth::user()->id;
        $input      = $request->all();      
        $check      = Contact::if_email_not_exist($input['email'],$input['group'],$user_id);
        $picture    = upload($request,'picture','contact');
        if($check){
            $insert     = [
                'first_name'    =>  ($input['first_name']) ? $input['first_name'] : '',
                'last_name'     =>  ($input['last_name']) ? $input['last_name'] : '',
                'email'         =>  ($input['email']) ? $input['email'] : '',
                'picture'       =>  $picture,
                'designation'   =>  ($input['designation']) ? $input['designation'] : '',
                'phone_number'  =>  ($input['phone_number']) ? $input['phone_number'] : '',
                'website'       =>  ($input['website']) ? $input['website'] : '',
                'facebook'      =>  ($input['facebook']) ? $input['facebook'] : '',
                'linkedin'      =>  ($input['linkedin']) ? $input['linkedin'] : '',
                'notes'         =>  ($input['notes']) ? $input['notes'] : '',
                'audio'         =>  ($input['recorded_file']) ? $input['recorded_file'] : '',
                'video'         =>  ($input['video']) ? $input['video'] : '',
                'user_id'       =>  $user_id,
                'website_ss'    =>  $input['website_ss'],
                'facebook_ss'   =>  $input['facebook_ss'],
                'linkedin_ss'   =>  $input['linkedin_ss']
            ];

            $contact_id = Contact::create($insert)->id;

            $group_member = ['group_id'=>$input['group'],'contact_id'=>$contact_id,'user_id'=>$user_id];
            Group_member::create($group_member);

            $request->session()->flash('success', 'Contact added successfuly.');
            return redirect()->route('home',['id'=>Crypt::encrypt($input['group'])]);
        }else{
            $request->session()->flash('error', 'Contact already in list.');
            return redirect()->route('home',['id'=>Crypt::encrypt($input['group'])]);
        }
    }

    public function view_contact(Request $request){
        $input  = $request->all();
        $id     = Crypt::decrypt($input['id']);
        $data['data']       = Contact::get_contact($id);
        $data['group_name'] = Contact::contact_group_name($id);
        return view('view_contact')->with($data);
    }

    public function add_group(Request $request){
        $input      = $request->all();      
        $picture    = upload($request,'picture','group');
        $insert     = ['group_name'=>$input['group_name'],'picture'=>$picture,'user_id'=>Auth::user()->id];
        Group::create($insert);
        return redirect()->route('home');
    }

    public function edit_contact(Request $resuest){
        $id                 = Crypt::decrypt($resuest->segment(2));
        $data['contact']    = Contact::get_contact($id);
        $data['groups']     = Group::get_all_groups(Auth::user()->id);
        return view('add_contact')->with($data);
    }

    public function update_contact(Request $request){
        $input      = $request->all();
        $id         = Crypt::decrypt($input['id']);
        $user_id    = Auth::user()->id;
        $check      = Contact::if_email_not_exist_except_him($input['email'],$input['group'],$id,$user_id);

        if($check){
            $update     = ['first_name'=>$input['first_name'],'last_name'=>$input['last_name'],'email'=>$input['email'],'designation'=>$input['designation'],'phone_number'=>$input['phone_number'],'website'=>$input['website'],'facebook'=>$input['facebook'],'linkedin'=>$input['linkedin'],'notes'=>$input['notes']];

            if(!empty($input['recorded_file'])){
                $update['audio'] = $input['recorded_file'];
            }

            if(!empty($input['video'])){
                $update['video'] = $input['video'];
            }

            if($request->file('picture')){   
                $picture            = upload($request,'picture','contact');
                $update['picture']  = $picture;
            }

            Contact::where('id',$id)->update($update);

            Group_member::where(['contact_id'=>$id,'user_id'=>$user_id])->update(['group_id'=>$input['group']]);

            $request->session()->flash('success', 'Contact updated successfuly.');
            return redirect()->route('home',['id'=>Crypt::encrypt($input['group'])]);
        }else{
            $request->session()->flash('error', 'Email id already in list.');
            return redirect()->route('home',['id'=>Crypt::encrypt($input['group'])]);
        }
    }

    public function unsubscribed_contacts(){
        $data['contacts']    = Contact::get_unsubscribed_contacts(Auth::user()->id);
        return view('unsubscribed')->with($data);
    }

    public function save_csv_contacts(Request $request){
        $input      = $request->all();
        $user_id    = Auth::user()->id;
        for($i=0;$i<count($input['first_name']);$i++){
            $audio      = '';
            $website_ss = '';
            $facebook_ss= '';
            $linkedin_ss= '';

            $insert     = [
                'first_name'    =>  ($input['first_name'][$i]) ? $input['first_name'][$i] : '',
                'last_name'     =>  ($input['last_name'][$i]) ? $input['last_name'][$i] : '',
                'email'         =>  ($input['email'][$i]) ? $input['email'][$i] : '',
                'designation'   =>  ($input['designation'][$i]) ? $input['designation'][$i] : '',
                'phone_number'  =>  ($input['phone_number'][$i]) ? $input['phone_number'][$i] : '',
                'website'       =>  ($input['website'][$i]) ? $input['website'][$i] : '',
                'facebook'      =>  ($input['facebook'][$i]) ? $input['facebook'][$i] : '',
                'linkedin'      =>  ($input['linkedin'][$i]) ? $input['linkedin'][$i] : '',
                'notes'         =>  ($input['notes'][$i]) ? $input['notes'][$i] : '',
                'audio'         =>  $audio,
                'user_id'       =>  $user_id,
                'website_ss'    =>  $website_ss,
                'facebook_ss'   =>  $facebook_ss,
                'linkedin_ss'   =>  $linkedin_ss
            ];

            $contact_id = Contact::create($insert)->id;

            $group_member = ['group_id'=>$input['group'],'contact_id'=>$contact_id,'user_id'=>$user_id];
            Group_member::create($group_member);
        }
        return redirect()->route('home');
    }

}
