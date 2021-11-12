@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Update Lottery</h2>
            </div>
         </div>
         <div class="row">
            @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
               {{ Session::get('error') }}
            </p>
            @endif
            @if(Session::has('success'))
            <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
               {{ Session::get('success') }}
            </p>
            @endif
         </div>
         <form action="/admin/lottery/edit/{{$lottery->lotteryID }}" name="lottery_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Name</label>
                     <input type="text" name="lotteryName" class="form-control" value="{{$lottery->lotteryName}}" required>
                  </div>
                  <div class="form-group">
                     <label>Total&nbsp;Participants</label>
                     <select name="entryLimit" class="form-control" required>
                        <option value="">Select Participants</option>
                        @for($i = 1; $i <= 100; $i++)
                           <option value="{{$i}}" @if($lottery->entryLimit == $i) selected='selected' @endif>{{$i}} Participants</option>
                        @endfor
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Entry Fee</label>
                     <input type="number" name="entryFees" class="form-control" value="{{$lottery->entryFees}}" min="1" required>
                  </div>
                  <div class="form-group">
                     <label>Win Amount</label>
                     <input type="number" name="winAmount" class="form-control" value="{{$lottery->winAmount}}" min="1" required>
                  </div>
                  <div class="form-group">
                     <label>Currency Code</label>
                     <input type="text" name="feeCurrency" class="form-control" value="{{$lottery->feeCurrency}}" required>
                  </div>
                  <div class="form-group">
                     <label>Status</label>
                     <select name="lotteryStatus" class="form-control" required>
                        <option value="">Select Status</option>
                        @foreach($statusEnum as $status)
                           <option value="{{$status}}" @if($lottery->lotteryStatus == $status) selected='selected' @endif>{{$status}}</option>
                        @endforeach
                     </select>
                  </div>
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                  <a href="/admin/lottery" class="btn btn-danger" >Cancel</a>
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection