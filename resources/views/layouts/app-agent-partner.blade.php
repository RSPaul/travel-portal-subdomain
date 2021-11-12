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
      <!-- BEGIN: Vendor CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/vendors.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/charts/apexcharts.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/extensions/toastr.min.css') }}">

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
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/plugins/charts/chart-apex.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/plugins/extensions/ext-component-toastr.css') }}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/plugins/forms/form-validation.css')}}">
      <!-- END: Page CSS-->
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/page-profile.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/extensions/sweetalert2.min.css')}}">
      <!-- BEGIN: Custom CSS-->

      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/core/menu/menu-types/vertical-menu.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/plugins/extensions/ext-component-sliders.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/app-ecommerce.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/plugins/extensions/ext-component-toastr.css')}}">

      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/app-chat.css')}}">
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/app-chat-list.css')}}">
      
      <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/style.css') }}">
      <!-- END: Custom CSS-->
   </head>
   <!-- END: Head-->
   <!-- BEGIN: Body-->
   <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static   menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">
      <input type="hidden" name="username" id="username" value="{{ $user->name }}">
      <!-- BEGIN: Header-->
      <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow">
         <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
               <ul class="nav navbar-nav d-xl-none">
                  <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
               </ul>
               <ul class="nav navbar-nav bookmark-icons">
                 <!--  <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Email"><i class="ficon" data-feather="mail"></i></a></li> -->
                  <!-- <li class="nav-item d-none d-lg-block"><a class="nav-link" href="/agent/chat" data-toggle="tooltip" data-placement="top" title="Chat"><i class="ficon" data-feather="message-square"></i></a></li> -->
                  <!-- <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Calendar"><i class="ficon" data-feather="calendar"></i></a></li>
                  <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#" data-toggle="tooltip" data-placement="top" title="Todo"><i class="ficon" data-feather="check-square"></i></a></li> -->
                  <li class="nav-item d-none d-lg-block"><a class="nav-link" href="/" data-toggle="tooltip" data-placement="top" title="Open Website" target="_blankblank"><i class="ficon" data-feather="globe"></i></a></li>
               </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
               <li class="nav-item dropdown dropdown-user">
                  <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <div class="user-nav d-sm-flex d-none"><span class="user-name font-weight-bolder">{{$user->name}}</span><span class="user-status">Affiliate</span></div>
                     <span class="avatar"><img class="round profile-pic-preview" src="/uploads/profiles/{{ $user->picture }}" alt="avatar" height="40" width="40"  onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';"><span class="avatar-status-online"></span></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user">
                     <!-- <a class="dropdown-item" href="/agent/profile"><i class="mr-50" data-feather="user"></i> Profile</a><a class="dropdown-item" href="/agent/chat"><i class="mr-50" data-feather="message-square"></i> Chats</a>
                     <div class="dropdown-divider"></div>
                     <a class="dropdown-item" href="/agent/settings"><i class="mr-50" data-feather="settings"></i> Settings</a> --><a class="dropdown-item" href="#" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();
                        signOut();"><i class="mr-50" data-feather="power"></i> Logout</a>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                     </form>
                  </div>
               </li>
            </ul>
         </div>
      </nav>

      <!-- END: Header-->
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
         <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
               <li class=" nav-item"><a class="d-flex align-items-center" href="/agent/withdraw-payment/{{$partnerName}}"><i data-feather='git-pull-request'></i><span class="menu-title text-truncate" data-i18n="Chat">Withdraw Request</span></a>
               </li>
               <li class=" nav-item"><a class="d-flex align-items-center" href="/agent/{{$partnerName}}"><i data-feather='dollar-sign'></i><span class="menu-title text-truncate" data-i18n="Chat">My Earnings</span></a>
               </li>
               
            </ul>
         </div>
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
      <!-- BEGIN: Page Vendor JS-->
      <script src="{{asset('/js/agent/js/charts/apexcharts.min.js')}}"></script>
      <script src="{{asset('/js/agent/js/extensions/toastr.min.js')}}"></script>
      <!-- END: Page Vendor JS-->
      <!-- BEGIN: Theme JS-->
      <script src="{{asset('/js/agent/core/app-menu.js')}}"></script>
      <script src="{{asset('/js/agent/core/app.js')}}"></script>
      <!-- END: Theme JS-->
      <!-- BEGIN: Page JS-->
      <script src="{{asset('/js/agent/scripts/pages/dashboard-ecommerce.js')}}"></script>
      <!-- END: Page JS-->
      <!-- <script src="{{asset('/js/agent/scripts/editors/quill/katex.min.js')}}"></script> -->
      <!-- <script src="{{asset('/js/agent/scripts/editors/quill/highlight.min.js')}}"></script> -->
      <!-- <script src="{{asset('/js/agent/scripts/editors/quill/quill.min.js')}}"></script> -->
      <script src="{{asset('/js/agent/scripts/pages/page-profile.js')}}"></script>
      <script src="{{asset('/js/agent/scripts/components/components-modals.js')}}"></script>
      <script src="{{asset('/js/agent/scripts/forms/form-validation.js')}}"></script>
      <!-- <script src="{{asset('/js/agent/scripts/pages/page-blog-edit.js')}}"></script> -->

      <script src="{{asset('/js/agent/js/extensions/wNumb.min.js')}}"></script>
      <script src="{{asset('/js/agent/js/extensions/nouislider.min.js')}}"></script>
      <script src="{{asset('/js/agent/js/extensions/toastr.min.js')}}"></script>

      <script src="{{asset('/js/agent/js/extensions/sweetalert2.all.min.js')}}"></script>

      <script src="{{asset('/js/agent/scripts/pages/app-ecommerce.js')}}"></script>
      <script src="{{asset('/js/agent/scripts/pages/app-chat.js')}}"></script>

      <!-- custom script -->
      <script type="text/javascript" src="{{'/js/agent/custom.js'}}"></script>
      <script>
         $(window).on('load', function() {
             if (feather) {
                 feather.replace({
                     width: 14,
                     height: 14
                 });
             }
         })
      </script>
   </body>
   <!-- END: Body-->
</html>