<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Lottery;

use Carbon\Carbon;


class LotteryController extends Controller
{   

    public function __construct(Request $request) {
        $this->middleware(['auth', 'isAdmin']);
        
    }

    public function index(Request $request) {

    	$lotteries = Lottery::orderBy('created_at', 'DESC')->get();
    	return view('admin.lottery.index')->with('lotteries', $lotteries);
    }

    public function add(Request $request) {
    	if($request->isMethod('post')) {
    		$input = $request->only('lotteryName', 'entryLimit', 'entryFees', 'winAmount', 'feeCurrency');
            
            try {

            	//first check if there is already active lottery
            	$active = Lottery::where('lotteryStatus', 'active')->first();
            	
            	if(isset($active) && !empty($active)) {

            		Session::flash('error', 'There is already one active lottery.');
            		return redirect('/admin/lottery/add')->with('error', 'There is already one active lottery.');	

            	} else {

            		$input['lotteryStatus'] = 'active';
            		Lottery::create($input);	
            		Session::flash('error', 'Lottery added Successfully.');
            		return redirect('/admin/lottery')->with('success', 'Lottery added Successfully.');	
            	}
            	

            } catch(Exception $e) {

            	return redirect('/admin/lottery/add')->with('error', $e->getMessage());
            }
            
        }
    	return view('admin.lottery.add');
    }

    public function edit(Request $request) {
    	$lottery = Lottery::where(['lotteryID' => $request->id])->first();	
        $statusEnum = array('active','inactive','withdraw','cancelled');
    	if($request->isMethod('post')) {
    		

    		$input = $request->only('lotteryName', 'entryLimit', 'entryFees', 'winAmount', 'feeCurrency', 'lotteryStatus');

            try {
               
               	if($input['lotteryStatus'] == 'active') {
                	//first check if there is already active lottery
            		$active = Lottery::where('lotteryStatus', 'active')->first();
            	} else {
            		$active = array();
            	}
            	
            	if(isset($active) && !empty($active)) {

            		Session::flash('error', 'There is already one active lottery.');
            		return redirect('/admin/lottery/edit/' . $request->id)->with('error', 'There is already one active lottery.');	

            	} else {
            
               		Lottery::where(['lotteryID' => $request->id])
            				->update($input);	
            		return redirect('/admin/lottery')->with('success', 'Cruise updated Successfully.');
            	}

            } catch(Exception $e) {

            	return redirect('/admin/lottery/edit/' . $request->id)->with('error', $e->getMessage());
            }
        }
    	return view('admin.lottery.edit')->with(['lottery' => $lottery, 'statusEnum' => $statusEnum]);
    }

    public function delete(Request $request) {
    	if(Auth::check() && Auth::user()->role == 'admin') {
            Lottery::where(['lotteryID' => $request->id])
                            ->delete();   
            return redirect('/admin/lottery')->with('success', 'Lottery deleted Successfully.');

        }
    }

}