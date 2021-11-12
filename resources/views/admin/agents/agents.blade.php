@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container admin-table">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Agents</h2>
            </div>
         </div>
         <div class="row">
            @if(Session::has('success'))
           <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
              {{ Session::get('success') }}
           </p>
           @endif
           @if(Session::has('error'))
           <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
              {{ Session::get('error') }}
           </p>
           @endif
         </div>
         <div class="form-group d-flex">  
            <a href="/admin/agent/add" class="btn btn-primary mr-auto" >Add Agents</a>
         </div>
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th>Name</th>
                           <th>Email</th>
                           <th>Referal Link</th>
                           <th>Commission</th>
                           <th>Status</th>
                           <th>Actions</th>
                        </tr>
                     </thead>                     
                     <tbody>
                        @foreach($users as $user)
                           <tr>
                              <td>{{$user->name}}</td>
                              <td>{{$user->email}}</td>
                              <td><a href="{{ env('REFFERAL_URL') }}/referral/{{$user->referal_code}}" target="_blank">{{ env('REFFERAL_URL') }}/referral/{{$user->referal_code}}</a></td>
                              <td>{{$user->commission}}%</td>
                              <td>
                                 @if($user->status == 1) 
                                    <a href="javascript:void(0);" class="btn btn-success btn-xs">
                                       <i class="fa fa-check" title="Approved Agent"></i>
                                    </a>
                                 @else
                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs">
                                       <i class="fa fa-times " title="Not Approved Agent"></i>
                                    </a>
                                 @endif
                              </td>
                              <td> 
                                 @if($user->status == 1) 
                                    <a href="javascript:void(0);" class="btn btn-warning btn-sm">Deactivate </a>
                                 @else
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm">Activate </a>
                                 @endif
                                 <a href="/admin/agent/edit/{{$user->id}}" class="btn btn-info btn-sm" title="View Agent Profile"><i class="fa fa-eye"></i> </a>&nbsp;<a href="javascript:void(0);" data-id="{{$user->id}}" class="btn btn-danger btn-sm delete-btn" title="Delete Agent Account"><i class="fa fa-trash"></i> </a>
                                 <form action="/admin/agent/delete/{{$user->id}}" method="POST" id="item_delete_{{$user->id}}">
                                    @csrf
                                    <input type="hidden" name="item_id" value="{{$user->id}}">
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