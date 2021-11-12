@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
               <h2 class="textlog">Hotels List</h2>
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
               <form id="searchFotelForm">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>Enter Hotel Name</label>
                           <input type="text" name="hotel_name" id="hotel_name" placeholder="Enter Hotel Name To Search" required class="form-control">
                        </div>
                        <div class="form-group">
                           <button type="submit" name="submit" id="searchHotelBtn" class="btn btn-primary">Search Hotels</button>
                        </div>
                        <div class="form-group">
                           <p id="hotelSearchError"></p>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
          <div class="card shadow mb-4">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-bordered dataTable addaddress" id="dataTable" width="100%" cellspacing="0">
                     <thead>
                        <tr>
                           <th width="30%">Hotel Name</th>
                           <th width="20%">City Name</th>
                           <th width="30%">Country Name</th>
                           <th width="20%">Actions</th>
                        </tr>
                     </thead>                     
                     <tbody id="hotelListBody">
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection