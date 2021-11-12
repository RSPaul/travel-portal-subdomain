@extends('layouts.app-header')
@section('content')  
<section class="state_flight_info" style="background: #f4f4f4 !important;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Registration for Lottery</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                <img src="{{asset('images/lottery.jpg')}}" class="img-responsive" />
            </div>
            <div class="col-4">

                @if(session('success'))
                <div class="alert alert-success txt-center" role="alert" >
                    <h4 style="padding-top:15px;padding-bottom:15px;text-align: center;" class="alert-heading">{{ session('success') }}</h4>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger txt-center" role="alert" >
                    <h4 style="padding-top:15px;padding-bottom:15px;text-align: center;" class="alert-heading">{{ session('error') }}</h4>
                </div>
                @endif

                @if (!Auth::guest())
                @if(!$isRegistered)
                <div id="error-message"></div>
                <div class="alert alert-info" role="alert" style="height: 100%;">
                    <h4 style="padding-top:15px;padding-bottom:15px;" class="alert-heading">Registration Required!</h4>
                    <hr/>
                    <form id="lotteryForm" style="padding-top:15px;" name="lotteryForm" method="POST"  action="{{ route('join_lottary') }}">
                        @csrf
                        <input type="hidden" name="RAZOR_KEY_ID" id="RAZOR_KEY_ID" value="{{ env('RAZOR_KEY_ID') }}"> 
                        <input type="hidden" name="lottery_fee"  id="lottery_fee" value="{{ $entryFees }}" >
                        <input type="hidden" name="lottery_fee_currency"  id="lottery_currency" value="{{ $feeCurrency }}" >
                        <input type="hidden" name="lottery_id"  id="lottery_id" value="{{ $lotteryID }}" >
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id" value="">   
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature" value=""> 
                        <input type="hidden"  value="{{ Auth::user()->email}}" class="form-control" id="lemail" name="lemail" aria-describedby="Email Address" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
                        <div class="form-group">
                            <label for="lname">User Name:<span class="text-danger">*</span></label>
                            <input type="text" value="{{ Auth::user()->name}}" class="form-control" name="lname" id="lname"  aria-describedby="User Name" required pattern="^[A-Za-z0-9 _]*[A-Za-z0-9][A-Za-z0-9 _]*$">
                        </div>
                        <div class="form-group">
                            <label for="lphone">Contact No:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lphone" name="lphone" aria-describedby="Contact" required >
                        </div>
                        <div class="form-group">
                            <label for="laddress">Address:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="laddress" id="laddress"  aria-describedby="Address" required >
                        </div>

                        <div class="form-group">
                            <label for="lotteryamnt">Participation Amount:</label>
                            <h4><strong>{{ $entryFees }}.00 {{ $feeCurrency }}</strong></h4>
                        </div>
                        <button type="submit" name="paynow" id="paynow1" onclick="joinLottery(event);" class="btn btn-primary">Pay Now</button>
                    </form>
                </div>
                @endif
                @else
                <div class="alert alert-primary" role="alert" style="height: 100%;">
                    <h4 style="padding-top:15px;padding-bottom:15px;" class="alert-heading">Registration Required!</h4>
                    <hr>
                    <p>Please login or register to participate into lottery system.</p>
                    <br/>
                    <a href="/login" class="btn btn-primary btn-lg btn-block">Login</a>
                    <br/>
                    <a href="/register" class="btn btn-secondary btn-lg btn-block">Register</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection