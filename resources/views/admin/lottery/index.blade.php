@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Lotteries</h2>
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
         <div class="form-group d-flex">  
            <a href="/admin/lottery/add" class="btn btn-primary mr-auto" >Add New</a>
         </div>
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th width="20%">Name</th>
                           <th width="10%">Total&nbsp;Participants</th>
                           <th width="10%">Active&nbsp;Participants</th>
                           <th width="10%">Entry&nbsp;Amount</th>
                           <th width="10%">Winning&nbsp;Amount</th>
                           <th width="10%">Status</th>
                           <th width="20%">Actions</th>
                        </tr>
                     </thead>                     
                     <tbody>
                        @foreach($lotteries as $lottery)
                           <tr>
                              <td>{{ $lottery->lotteryName }}</td>
                              <td>{{ $lottery->entryLimit }}</td>
                              <td>{{ $lottery->entryLimit }}</td>
                              <td>{{ $lottery->entryFees }}</td>
                              <td>{{ $lottery->winAmount }}</td>
                              <td>{{ $lottery->lotteryStatus }}</td>
                              <td><a href="/admin/lottery/edit/{{ $lottery->lotteryID }}" class="btn btn-info btn-sm">Edit </a> <a href="javascript:void(0);" data-id="{{ $lottery->lotteryID }}" data-status="{{ $lottery->lotteryStatus }}" class="btn btn-danger btn-sm delete-btn-lottery">Delete </a>
                                 <form action="/admin/lottery/delete/{{ $lottery->lotteryID }}" method="POST" id="item_delete_{{ $lottery->lotteryID }}">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{ $lottery->lotteryID }}">
                                 </form>
                              </td>
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