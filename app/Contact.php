<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    protected $fillable = [
        'first_name','last_name','email','picture','designation','phone_number','website','facebook','linkedin','notes','audio','user_id','website_ss','facebook_ss','linkedin_ss','video'
    ];

    public static function get_contacts_by_group_id($id){
        $data = DB::table('group_members')->select(DB::raw('*,contacts.id,contacts.picture'))->join('contacts', 'group_members.contact_id', '=', 'contacts.id')->join('groups','group_members.group_id','=','groups.id')->where('group_members.group_id','=',$id)->get()->toArray();
        return $data;
    }

    public static function get_contacts_by_group_id_new($id){
        
        if(!empty(@$_GET['search'])){
            $search = $_GET['search'];
            $data = DB::table('group_members')->select(DB::raw('*,contacts.id,contacts.picture'))->join('contacts', 'group_members.contact_id', '=', 'contacts.id')->join('groups','group_members.group_id','=','groups.id')->where(function($query) use($id){
                $query->where('group_members.group_id','=',$id);
            })->where(function($query) use($search){
                $query->where('first_name','LIKE','%'.$search.'%')->orWhere('last_name','LIKE','%'.$search.'%')->orWhere('email','LIKE','%'.$search.'%')->orWhere('facebook','LIKE','%'.$search.'%')->orWhere('website','LIKE','%'.$search.'%');
            })->orderBy('group_members.id','DESC')->paginate(10)->toArray();
        }else{
            $data = DB::table('group_members')->select(DB::raw('*,contacts.id,contacts.picture'))->join('contacts', 'group_members.contact_id', '=', 'contacts.id')->join('groups','group_members.group_id','=','groups.id')->where('group_members.group_id','=',$id)->orderBy('group_members.id','DESC')->paginate(10)->toArray();
        }
        return $data;
    }

    public static function get_contact($id){
        $data = DB::table('contacts')->where('id','=',$id)->get()->first();
        return $data;
    }

    public static function get_unsubscribed_contacts($id){
        $data = DB::table('contacts')->where(['user_id'=>$id,'unsubscribed'=>1])->get()->toArray();
        return $data;
    }

    public static function contact_group_name($id){
        $data = DB::table('group_members')->select(DB::raw('groups.*'))->join('groups','groups.id','=','group_members.group_id')->where(['contact_id'=>$id])->get()->first();
        return $data->group_name;
    }

    public static function if_email_not_exist($email,$group,$user_id){
        $data = DB::table('group_members')->select(DB::raw('*'))->join('contacts', 'group_members.contact_id', '=', 'contacts.id')->join('groups','group_members.group_id','=','groups.id')->where('group_members.group_id','=',$group)->where('contacts.email','=',$email)->get()->toArray();
        if(count($data)>0){
            return false;
        }else{
            return true;
        }
    }

    public static function if_email_not_exist_except_him($email,$group,$contact_id,$user_id){
        $data = DB::table('group_members')->select(DB::raw('*'))->join('contacts', 'group_members.contact_id', '=', 'contacts.id')->join('groups','group_members.group_id','=','groups.id')->where('group_members.group_id','=',$group)->where('contacts.email','=',$email)->where('contacts.id','!=',$contact_id)->get()->toArray();
        if(count($data)>0){
            return false;
        }else{
            return true;
        }
    }

}
