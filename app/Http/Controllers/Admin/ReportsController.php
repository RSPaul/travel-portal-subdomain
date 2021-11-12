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


use DB;
use Mail;
use App\Models\User;

class ReportsController extends Controller
{   
    
    public function __construct(Request $request) {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function visitorsReports(Request $request) {

        return view('admin.reports.visitors');

    }

    public function salesReports(Request $request) {

    	return view('admin.reports.sales');

    }

    public function earningReports(Request $request) {

        return view('admin.reports.earnings');

    }
}