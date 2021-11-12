@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Withdrawls Requests</h2>
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
                           <th >Name</th>
                           <th >Email</th>
                           <th >Payment</th>
                           <th >Status</th>
                           <th >Actions</th>
                        </tr>
                     </thead>                     
                     <tbody>
                      	@foreach($results as $result)
                      		<tr>
                      			<td>
                      				{{ $result['user']['name'] }}
                      			</td>
                      			<td>
                      				{{ $result['user']['email'] }}
                      			</td>
                      			<td>
                      				${{ number_format(($result['amount'] - 500) ,2) }}
                      			</td>
                      			<td>
                      				Pending
                      			</td>
                      			<td>
                      				<a href="javascript:void(0);" class="btn btn-primary approve-payment" data-amount="{{ number_format(($result['amount'] - 500),2) }}" data-user="{{ $result['user']['id'] }}">Approve</a>
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