<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      
      <link href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

      <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

      <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
      <link rel="icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">  
      
      <!-- Custom styles for this template -->
      <link href="{{asset('css/admin/css/newthe.css')}}" rel="stylesheet">
      <link href="{{asset('vendor/datepicker/daterangepicker.css')}}" rel="stylesheet" media="all">
      <link href="{{asset('css/admin/css/fullcalendar/main.min.css')}}" type="text/css" rel="stylesheet" />

      <link href="{{asset('js/admin/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
      <link href="{{asset('css/admin/css/admin.dev.css')}}" rel="stylesheet">
      <link href="{{asset('css/admin/css/sweetalert.css')}}" rel="stylesheet">
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet" type="text/css" />

      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

      <!-- CK editor -->
      <link href="{{asset('js/admin/sample/css/sample.css')}}" rel="stylesheet">
     
    
   </head>
   <body>
      <div class="" id="wrapper">
         <nav class="navbar navbar-expand-lg navbar-light" id="mainNav">
            <div class="container-fluid">
               <a class="navbar-brand js-scroll-trigger logomain" href="/admin">
                  <img src="{{asset('images/logo.png')}}" >                  
               </a>
               <button class="navbar-toggler navbar-toggler-right buttonmenu" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fas fa-bars"></i>
               </button>
               <div class="loginlogout">
                  <div class="dropdown">
                     <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <p>{{Auth::user()->name}}<p>
                     </button> 
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/admin/profile">Edit Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           @csrf
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </nav>
         <div id="page-content-wrapper" class="d-flex">
            <div class="bl" id="sidebar-wrapper">
               <div class="list-group list-group-flush">
                  <!-- <a href="/admin/activities" class="list-group-item list-group-item-action">Activities</a> -->
                  <a href="/admin/cruises" class="list-group-item list-group-item-action">Cruises</a>
                  <!-- <a href="/admin/packages" class="list-group-item list-group-item-action">Packages</a> -->
                  <a href="/admin/cabs" class="list-group-item list-group-item-action">Cabs</a>
                  <!-- <a href="/admin/hotel/details" class="list-group-item list-group-item-action">Hotel Info</a> -->
                  <!-- <a href="/admin/hotel-room-images" class="list-group-item list-group-item-action">Room Images</a> -->
                  <!-- <a href="/admin/import/rooms" class="list-group-item list-group-item-action">Hotel Rooms</a> -->
                  <a href="/admin/meta" class="list-group-item list-group-item-action">Meta Tags</a>
                  <a href="/admin/bookings" class="list-group-item list-group-item-action">Bookings</a>
               </div>
            </div>

            @yield('content')
         </div>
      </div>
      <!-- Bootstrap core JavaScript -->
      <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
      <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
      <script src="{{asset('js/admin/datatables/jquery.dataTables.min.js')}}"></script>
      <script src="{{asset('js/admin/datatables/dataTables.bootstrap4.min.js')}}"></script>
      <script src="{{asset('vendor/datepicker/moment.min.js')}}"></script>
      <script src="{{asset('vendor/datepicker/daterangepicker.js')}}"></script>
      <script src="{{asset('js/admin/fullcalendar/main.min.js')}}"></script>
      <script src="{{asset('js/admin/sweetalert.min.js')}}"></script>

      <!-- ckeditor -->
      <script src="{{asset('js/admin/ckeditor.js')}}" ></script>
      <script src="{{asset('js/admin/bootstrap-datepicker.js')}}"></script>

      <script src="{{asset('js/admin/jquery-ui.js')}}"></script>
      <!-- Custom scripts for this template -->
      <script src="{{asset('js/admin/admin.js')}}" ></script>
      <script src="{{asset('js/admin/editors.js')}}" ></script>

   </body>
</html>