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
use App\Models\Bookings;
use App\Models\Payments;
use App\Models\FlightPayments;
use App\Models\FlightBookings;
use App\Models\AffiliateUsers;
use App\Models\Currencies;
use App\Models\Posts;
use App\Models\PostComments;
use App\Models\PostLikes;
use App\Models\NotificationAgents;
//use Currency;

class ProfileController extends Controller
{

	public $end_user_ip;

    public function __construct()
    {
        $this->middleware(['auth', 'isAgent'], array('except' => array('viewProfile')));

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip();
        if ($ip == '127.0.0.1') {

            $ip = '132.154.175.244';//'185.191.207.36'; //'93.173.228.94';

        }
        $this->end_user_ip = $ip;

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? \Request::ip();
        if ($ip == '127.0.0.1') {
            $ip = '103.40.200.110';
        }
        $this->end_user_ip = $ip;

        // $location = \Location::get($this->end_user_ip);
        // //get user currency
        // $countryInfo = Currencies::where('code', $location->countryCode)->first();
        // Session::put('CurrencyCode', $countryInfo['currency_code']);        

    }

    public function index(Request $request) {

        $user = User::find(Auth::user()->id);
        $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
        $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();

        $posts = Posts::join('users', 'users.id', '=', 'posts.user_id')
                        ->select('posts.*', 'users.name', 'users.picture', 'users.id as userId')
                        ->orderBy('created_at', 'DESC')->get();

        $photos = Posts::where(['post_type' => 'article_image', 'user_id' => Auth::user()->id])
                            ->orderBy('created_at', 'DESC')->get();
        
        foreach ($posts as $key => $post) {
            $comments = PostComments::where('post_id',$post->id)
                        ->join('users', 'users.id', '=', 'post_comments.user_id')
                        ->select('post_comments.comment', 'post_comments.created_at', 'users.name', 'users.picture', 'users.id as userId')
                        ->orderBy('created_at', 'DESC')
                        ->get();

            $likes = PostLikes::where('post_id',$post->id)
                        ->join('users', 'users.id', '=', 'post_likes.user_id')
                        ->select('users.name', 'users.picture', 'users.id as userId')
                        ->get();

            $liked = PostLikes::where(['post_id' => $post->id, 'user_id' => Auth::user()->id])->first();

            if(isset($liked) && !empty($liked)) {

                $posts[$key]['liked'] = '1';

            } else {

                $posts[$key]['liked'] = '0';
            }

            $posts[$key]['comments'] = $comments;
            $posts[$key]['likes'] = $likes;
        }
        
        return view('agent.profile')->with(['user' => $user, 'notifications' => $notifications, 'agent' => $agent, 'posts' => $posts, 'photos' => $photos]);
    }

    public function viewProfile(Request $request) {

        if(Auth::check()) {
            $user = User::find(Auth::user()->id);
            $agent = AffiliateUsers::where('user_id', Auth::user()->id)->first();
            $notifications = NotificationAgents::where(['agent_id' => Auth::user()->id, 'status' => '0'])->get();
        } else{
            $user = new User();
            $agent = new AffiliateUsers();
            $notifications = array();
        }


        $user_select = User::find($request->id);
        $agent_select = AffiliateUsers::where('user_id', $request->id)->first();

        $posts = Posts::join('users', 'users.id', '=', 'posts.user_id')
                        ->select('posts.*', 'users.name', 'users.picture', 'users.id as userId')
                        ->orderBy('created_at', 'DESC')
                        ->where('user_id', $request->id)
                        ->get();
        $photos = Posts::where(['post_type' => 'article_image', 'user_id' => $request->id])
                            ->orderBy('created_at', 'DESC')->get();

        foreach ($posts as $key => $post) {
            $comments = PostComments::where('post_id',$post->id)
                        ->join('users', 'users.id', '=', 'post_comments.user_id')
                        ->select('post_comments.comment', 'post_comments.created_at', 'users.name', 'users.picture', 'users.id as userId')
                        ->orderBy('created_at', 'DESC')
                        ->get();

            $likes = PostLikes::where('post_id',$post->id)
                        ->join('users', 'users.id', '=', 'post_likes.user_id')
                        ->select('users.name', 'users.picture', 'users.id as userId')
                        ->get();

            $liked = PostLikes::where(['post_id' => $post->id, 'user_id' =>$request->id])->first();

            if(isset($liked) && !empty($liked)) {

                $posts[$key]['liked'] = '1';

            } else {

                $posts[$key]['liked'] = '0';
            }

            $posts[$key]['comments'] = $comments;
            $posts[$key]['likes'] = $likes;
        }
        
        return view('agent.view-profile')->with(['user' => $user, 'notifications' => $notifications, 'agent' => $agent, 'posts' => $posts, 'photos' => $photos, 'user_select' => $user_select, 'agent_select' => $agent_select]);
    }
}