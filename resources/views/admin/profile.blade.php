@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Profile</h2>
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
         <form action="/admin/profile" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Name</label>
                     <input type="text" name="name" class="form-control" value="{{$user->name}}" required placeholder="Name">
                  </div>
                   <div class="form-group">
                     <label>Address</label>
                     <input type="text" name="address" class="form-control" value="{{$user->address}}" required placeholder="Address">
                  </div>
                  <div class="form-group">
                     <label>Phone</label>
                     <input type="text" name="phone" class="form-control" value="{{$user->phone}}">                     
                  </div>     
                  <div class="form-group">
                    <label>Country</label>
                    <input list="country" id="countryInput" name="country"  value="{{$user->country}}" placeholder="Choose your country" class="form-control" required>
                    <datalist id="country">
                      @foreach($currencies as $currency)
                         <option class="data-list" value="{{$currency->code}}" data-currency="{{$currency->currency_code}}" data-country="{{$currency->code}}">
                      @endforeach
                    </datalist> 
                  </div>                              
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Email</label>
                     <input type="email" name="email" class="form-control" value="{{$user->email}}" required placeholder="Email">
                  </div>
                  <div class="form-group">
                     <label>Password <span class="info">(Leave blank if don't want to update)</span></label>
                     <input type="password" name="password" class="form-control" value="" placeholder="Password">
                  </div>
                  <div class="form-group">
                     <label>Confirm Password</label>
                     <input type="password" name="confirm_password" class="form-control" value="" placeholder="Confirm Password">
                  </div>
               </div>
            </div>
         </form>
      </div>
   </section>

   <!-- Update Weekend Image from Admin Panel   -->

   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Weekend/Holiday Image</h2>
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
         <form action="/admin/saveWeekendImage" name="image_update" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Upload Image (Desktop)</label>
                     <input type="file" name="weekend_image" class="form-control" value="">
                     <br>
                     @if(isset($weekend_images['web_image']))
                        <img src="/uploads/weekend_images/{{$weekend_images['web_image']}}" width="200px" height="150px">
                     @endif
                  </div>
                  
                                             
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                     <label>Upload Image (Mobile)</label>
                     <input type="file" name="weekend_image_mobile" class="form-control" value="">
                     <br>
                     @if(isset($weekend_images['mobile_image']))
                        <img src="/uploads/weekend_images/{{$weekend_images['mobile_image']}}" width="200px" height="150px">
                     @endif
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Coming Soon Mode</label>
                     <select  name="coming_soon_mode" class="form-control" >
                        <option value="1" @if(isset($weekend_images['coming_soon_mode']) && $weekend_images['coming_soon_mode'] == '1') selected="selected" @endif>Activated</option>
                        <option value="0" @if(isset($weekend_images['coming_soon_mode']) && $weekend_images['coming_soon_mode'] == '0') selected="selected" @endif>Not Activated</option>
                     </select>
                  </div>
                  
                                             
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="form-group">
                     <label>Choose Date Time</label>
                     <input type="text" name="banner_time" class="form-control" value="@if(isset($weekend_images['banner_time'])){{ $weekend_images['banner_time'] }}@endif" placeholder="2021-04-04 21:30:00">
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
            </div>
         </form>
      </div>
   </section>

   <!-- Ends Here -->
</div>
@endsection