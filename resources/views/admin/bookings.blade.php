@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Comission From Main Website</h2>
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
         <!-- <div class="form-group d-flex">  
            <a href="/admin/hotel-room-images/add" class="btn btn-primary mr-auto" >Add Images</a>
         </div> -->
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th width="33%">Booking Date</th>
                           <!-- <th width="33%">Booking Amount</th> -->
                           <th width="33%">Comission Earned</th>
                           <th width="33%">Booking Type</th>
                        </tr>
                     </thead>                     
                     <tbody>
                        @foreach($bookings as $booking)
                           <tr>
                              <td>{{$booking->created_at}}</td>
                              <!-- <td>{{$booking->currency_code}}&nbsp;{{number_format($booking->total_paid,2)}}</td> -->
                              <td>{{$booking->currency_code}}&nbsp;{{number_format($booking->comission_earned,2)}}</td>
                              <td>Hotel</td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection