<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
   <!-- BEGIN: Head-->
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
      <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
      <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
      <meta name="author" content="PIXINVENT">
      <title>
         @if(isset($title) && !empty($title))
         {{ env('APP_NAME') }} - {{ $title }}
         @else
         {{ env('APP_NAME') }} - Affiliate Dashboard
         @endif        
      </title>

      <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css') }}">
      <link rel="apple-touch-icon" href="{{asset('/css/agent/images/ico/apple-icon-120.png')}}') }}">
      <link rel="shortcut icon" type="image/x-icon" href="{{asset('/css/agent/images/ico/favicon.ico') }}">
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">


      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/vendors.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/extensions/nouislider.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/extensions/toastr.min.css') }}">

      <!-- END: Vendor CSS-->
      <!-- BEGIN: Theme CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/bootstrap.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/bootstrap-extended.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/colors.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/components.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/themes/dark-layout.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/themes/bordered-layout.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/themes/semi-dark-layout.css') }}">
      <!-- BEGIN: Page CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/core/menu/menu-types/vertical-menu.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/dashboard-ecommerce.css') }}">
      <!-- END: Page CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/page-profile.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/extensions/sweetalert2.min.css')}}">
      <!-- BEGIN: Custom CSS-->

      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/core/menu/menu-types/vertical-menu.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/plugins/extensions/ext-component-sliders.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/app-ecommerce.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/plugins/extensions/ext-component-toastr.css')}}">
      
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/style.css') }}">
      <!-- END: Custom CSS-->
   </head>
   <!-- END: Head-->
   <!-- BEGIN: Body-->
   <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">

      <!-- BEGIN: Main Menu-->
      <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
         <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
               <li class="nav-item mr-auto">
                  <a class="navbar-brand" href="/agent/dashboard">
                     <span class="brand-logo">
                        <img src="{{asset('/images/logo.png')}}">
                     </span>
                     <h2 class="brand-text">Tripheist</h2>
                  </a>
               </li>
               <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
         </div>
         <div class="shadow-bottom"></div>
      </div>
      <!-- END: Main Menu-->
      <!-- BEGIN: Content-->
      @yield('content')
      <!-- END: Content-->
      <div class="sidenav-overlay"></div>
      <div class="drag-target"></div>
      <!-- BEGIN: Footer-->
      <footer class="footer footer-static footer-light">
      </footer>
      <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
      <!-- END: Footer-->
      <!-- BEGIN: Vendor JS-->
      <script src="{{asset('/js/agent/js/vendors.min.js')}}"></script>
      <!-- BEGIN Vendor JS-->
      <!-- BEGIN: Theme JS-->
      <script src="{{asset('/js/agent/core/app-menu.js')}}"></script>
      <script src="{{asset('/js/agent/core/app.js')}}"></script>
      <!-- END: Theme JS-->
      <!-- <script src="{{asset('/js/agent/scripts/editors/quill/katex.min.js')}}"></script> -->
      <!-- <script src="{{asset('/js/agent/scripts/editors/quill/highlight.min.js')}}"></script> -->
      <!-- <script src="{{asset('/js/agent/scripts/editors/quill/quill.min.js')}}"></script> -->
      <script src="{{asset('/js/agent/scripts/pages/page-profile.js')}}"></script>

      <script src="{{asset('/js/agent/js/extensions/wNumb.min.js')}}"></script>
      <script src="{{asset('/js/agent/js/extensions/nouislider.min.js')}}"></script>
      <script src="{{asset('/js/agent/js/extensions/toastr.min.js')}}"></script>
   </body>
   <!-- END: Body-->
</html>