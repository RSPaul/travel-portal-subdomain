<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Reponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Mail;
use DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Messages;
use App\Models\AffiliateUsers;
use App\Models\NotificationAgents;

class ChatController extends Controller
{
	public function __construct()
    {
        $this->middleware(['auth', 'isAgent']);  
    }

    public function chat(Request $request) {

    	$notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
    	$agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();
    	$agents = User::where('role', 'agent')->where('users.id', '<>', Auth::user()->id)->get();
    	$messages = Messages::where(function($q) {
					          $q->where('sender_id', Auth::user()->id)
					            ->orWhere('reciever_id', Auth::user()->id);
					      	})
    						->select('sender_id', 'reciever_id', 'message', 'created_at')
    						->orderBy('created_at', 'DESC')
    						->get();

    	$chats = array();

    	foreach($messages as $m) {
    		
    		if(Auth::user()->id != $m->sender_id) {
				$user_id = $m->sender_id;
			} else {
				$user_id = $m->reciever_id;
			}

    		if(!empty($chats)) {
    			$matched = false;
    			foreach ($chats as $c) {
    				if($c['user_id'] == $user_id) {
    					$matched = true;
    				}
    			}

    			if(!$matched) {

    				$u = User::where('id', $user_id)->first();
    				$unread = Messages::where('status', 'unread')
    							->where('reciever_id', Auth::user()->id)
					      		->count();

    				array_push($chats, array('name' => $u['name'], 'picture' => $u['picture'], 'message' => $m->message, 'user_id' => $user_id, 'time' => $m->created_at, 'unread' => $unread, 'phone' => $u->phone));
    			}

    		} else {
    			
    			$u = User::where('id', $user_id)->first();
    			$unread = Messages::where('status', 'unread')
    							->where('reciever_id', Auth::user()->id)
					      		->count();
    			array_push($chats, array('name' => $u['name'], 'picture' => $u['picture'], 'message' => $m->message, 'user_id' => $user_id, 'time' => $m->created_at, 'unread' => $unread, 'phone' => $u->phone));
    		}
    	}

    	return view('agent.chat')->with(['user' => Auth::user(), 'notifications' => $notifications, 'agent' => $agent, 'agents' => $agents, 'chats' => $chats]);

    }
}