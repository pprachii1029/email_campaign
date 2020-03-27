<?php

namespace Email_Campaign\Http\Controllers;

use Illuminate\Http\Request;
use Email_Campaign\Group;
use Email_Campaign\Contact;
use Email_Campaign\Group_member;
use Email_Campaign\Suppression;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SuppressionController extends Controller
{
    public function __construct(){
        $this->middleware(['auth','verified']);
    }

    public function index(){
        $data['suppressions'] = Suppression::get_all_suppression(Auth::user()->id);
        return view('suppression')->with($data);
    }

    public function add_suppression(){
        $data['groups'] = Group::get_all_groups(Auth::user()->id);
        return view('add_suppression')->with($data);
    }

    public function save_suppression(Request $request){
        $input = $request->all();
        $input['user_id'] = Auth::user()->id;
        Suppression::create($input);
        return redirect()->route('suppression');
    }
}
