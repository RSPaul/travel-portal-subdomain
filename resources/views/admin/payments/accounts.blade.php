@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Bank Accounts</h2>
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
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th width="10%">Name</th>
                           <th width="20%">Bank&nbsp;Name</th>
                           <th width="10%">Account&nbsp;No.</th>
                           <th width="10%">Swift&nbsp;Code</th>
                           <th width="10%">Address</th>
                           <th width="10%">Mobile&nbsp;Number</th>
                           <th width="10%">Zip&nbsp;Code</th>
                           <th width="10%">Status</th>
                           <th width="10%">Actions</th>
                        </tr>
                     </thead>                     
                     <tbody>
                        @foreach($accounts as $account)
                           <tr>
                              <td>{{ $account->first_name }}&nbsp;{{ $account->first_name }}</td>
                              <td>{{ $account->bank_name }}</td>
                              <td>{{ $account->account_number }}</td>
                              <td>{{ $account->swift_code }}</td>
                              <td>{{ $account->address }}</td>
                              <td>{{ $account->mobile_number }}</td>
                              <td>{{ $account->zip_code }}</td>
                              <td>
                                 @if($account->verified == 'no')
                                    Verification Pending
                                 @else
                                    Verified
                                 @endif
                              </td>
                              <td>
                                 @if($account->verified == 'no')
                                    <a href="javascript:void(0);" class="btn btn-warning approve-account" data-id="{{ $account->id }}" data-user="{{ $account->user_id }}">Approve</a>
                                 @else
                                    <a href="javascript:void(0);" class="btn btn-success" disabled>Approved</a>
                                 @endif
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