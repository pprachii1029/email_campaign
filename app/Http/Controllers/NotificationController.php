<?php

namespace Email_Campaign\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Email_Campaign\Group;
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
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    public function notifications(){
    	$user_id = Auth::user()->id;
    	$notifications 	= Notification::get_all_notifications($user_id);
    	$data			= $notifications;
    	$data['notifications'] = $notifications['data'];
    	unset($data['data']);
    	
    	return view('notification')->with($data);
    }
}
