<?php

namespace Email_Campaign;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'contact_id','history_id','message','message_type','is_read','time'
    ];

    public static function get_all_notifications($id){
    	$data = DB::table('notifications')->select(DB::raw('notifications.*,contacts.id,contacts.first_name,contacts.last_name,contacts.picture,histories.video_link'))->join('contacts', 'notifications.contact_id', '=', 'contacts.id')->join('histories','histories.id','=','notifications.history_id')->where('time','!=','00:00')->where('time','!=','00:00:00')->where('time','!=','0')->where('notifications.user_id','=',$id)->orderBy('notifications.id','DESC')->paginate(10)->toArray();
    	return $data;
    }
}
