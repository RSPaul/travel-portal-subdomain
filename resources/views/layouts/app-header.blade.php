<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>        
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="facebook-domain-verification" content="hyzno2isfiqfsuyq2834lhrxuqllb1" />
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/custom.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
        <!-- Owl Stylesheets -->
        <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/animate.css') }}">
        <!--Custom select Stylesheet-->
        <!-- <link rel="stylesheet" type="text/css" href="{{asset('css/jquery_select.css') }}"> -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/select2.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/dev.css') }}">
        <link href="{{asset('css/datepicker.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="{{asset('css/parsley.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/simpleLightbox.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap4-toggle.min.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <!--      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjvgyeR3nVJ0R71qyNP6WiOcXpskcXzFw"></script>-->
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initAutocomplete&libraries=places&v=weeklysensor=false&language=en"defer ></script>
        <meta name="google-signin-client_id" content="847542483996-sk24e969e0rrpphk4gdiuslg83gjss2i.apps.googleusercontent.com">
        <script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
        <script src="{{ asset('js/jquery-3.3.1.min.js')}}"></script>
        <script src="https://cdn.paymeservice.com/hf/v1/hostedfields.js"></script>
        <script src="{{ asset('js/payme-api.js')}}"></script>
        <title>
            @if(isset($title) && !empty($title))
                {{ env('APP_NAME') }} - {{ $title }}
            @else
                {{ env('APP_NAME') }}
            @endif
        </title>
        @if(isset($meta_image))
            <meta property="og:title" content="{{ env('APP_NAME') }} - {{ $title }}">
            <meta name="twitter:title" content="{{ env('APP_NAME') }} - {{ $title }}">
            <meta property="og:image" content="{{$meta_image}}">
            <meta property="twitter:image" content="{{$meta_image}}">
            <meta property="og:url" content="{{Request::url()}}">
            <meta property="og:type" content="article">
            <meta property="og:description" content="{{ env('APP_NAME') }} - {{ $title }}">
            <meta property="fb:app_id" content="703033047049864">
        @endif
       
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-BH8JQJHE3E"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'G-BH8JQJHE3E');
          gtag('config', 'UA-190132872-3');
        </script>

    </head>
    <body>
        <header class="main_header">
            <nav class="navbar navbar-expand-lg">
                <div class="container">

                    <a class="navbar-brand" href="/"><img src="{{asset('images/logo.png')}}" alt="logo"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                        <span class="navbar-toggler-icon"></span>
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="mobileMenuDiv">
                        <ul class="navbar-nav mr-auto">

                            @if( Auth::check())
                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'flights') active @endif" data-tab="flights">
                                <a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/plane-icon.png')}}" alt="flight"><img class="selected_img" src="{{asset('images/plane-icon.png')}}" alt="flights"><span>{{ __('labels.flights') }}</span></a>
                            </li>
                            @endif

                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'hotels') active @endif" data-tab="hotels"><a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/hotel-icon.png')}}" alt="hotels"><img class="selected_img" src="{{asset('images/hotel-icon.png')}}" alt="hotels"><span>{{ __('labels.hotels') }}</span></a></li>

                            <!-- <li  class="nav-item menu-link @if(Session::get('active_tab') == 'flights-hotels') active @endif" data-tab="flights-hotels"><a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/flight-hotel-icon.png')}}" alt="flight+hotels"><img class="selected_img" src="{{asset('images/flight-hotel-icon.png')}}" alt="flight+hotels"><span>{{ __('labels.flights-hotels') }}</span></a></li> -->

                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'activities') active @endif" data-tab="activities"><a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/activities-icon.png')}}" alt="flight"><img class="selected_img" src="{{asset('images/activities-icon.png')}}" alt="flights"><span>{{ __('labels.activities') }}</span></a></li>
                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'cabs') active @endif" data-tab="cabs">
                                <a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/cabs-icon.png')}}" alt="Cab"><img class="selected_img" src="{{asset('images/cabs-icon.png')}}" alt="cabs"><span>{{ __('labels.cabs') }}</span></a>
                            </li>
                             <li class="nav-item menu-link"><a class="nav-link" href="/coming_soon"  data-tab="/coming_soon"><img class="unselected_img" src="{{asset('images/package-icon.png')}}" alt="flight"><img class="selected_img" src="{{asset('images/package-icon.png')}}" alt="flights"><span>Packages</span></a></li>
                            <li class="nav-item menu-link"><a class="nav-link" href="/coming_soon"  data-tab="/coming_soon"><img class="unselected_img" src="{{asset('images/cruise-icon.png')}}" alt="Cruise"><img class="selected_img" src="{{asset('images/cruise-icon.png')}}" alt="Cruise"><span>Cruise</span></a></li> 

                            <li>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-language"></i> {{ app()->getLocale() }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item change-locale" data-lang="en" href="{{ url('locale/en') }}">English</a>
                                        <a class="dropdown-item change-locale" data-lang="hi" href="{{ url('locale/hi') }}">हिंदी</a>
                                        <a class="dropdown-item change-locale" data-lang="heb" href="{{ url('locale/heb') }}">עִברִית</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarsExample03">

                        <ul class="navbar-nav mr-auto">

                            @if(Auth::check())
                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'flights') active @endif" data-tab="flights">
                                <a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/plane-icon.png')}}" alt="flight"><img class="selected_img" src="{{asset('images/plane-icon.png')}}" alt="flights"><span>{{ __('labels.flights') }}</span></a>
                            </li>
                            @endif

                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'hotels') active @endif" data-tab="hotels"><a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/hotel-icon.png')}}" alt="hotels"><img class="selected_img" src="{{asset('images/hotel-icon.png')}}" alt="hotels"><span>{{ __('labels.hotels') }}</span></a></li>
                            
                            <!-- <li  class="nav-item menu-link @if(Session::get('active_tab') == 'flights-hotels') active @endif" data-tab="flights-hotels"><a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/flight-hotel-icon.png')}}" alt="flight+hotels"><img class="selected_img" src="{{asset('images/flight-hotel-icon.png')}}" alt="flight+hotels"><span>{{ __('labels.flights-hotels') }}</span></a></li> -->

                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'activities') active @endif" data-tab="activities"><a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/activities-icon.png')}}" alt="flight"><img class="selected_img" src="{{asset('images/activities-icon.png')}}" alt="flights"><span>{{ __('labels.activities') }}</span></a></li>
                            <li class="nav-item menu-link @if(Session::get('active_tab') == 'cabs') active @endif" data-tab="cabs">
                                <a class="nav-link" href="javascript:void(0);" ><img class="unselected_img" src="{{asset('images/cabs-icon.png')}}" alt="Cab"><img class="selected_img" src="{{asset('images/cabs-icon.png')}}" alt="cabs"><span>{{ __('labels.cabs') }}</span></a>
                            </li>

                            <!-- <li class="nav-item menu-link"><a class="nav-link" href="/coming_soon"  data-tab="/coming_soon"><img class="unselected_img" src="{{asset('images/package-icon.png')}}" alt="flight"><img class="selected_img" src="{{asset('images/package-icon.png')}}" alt="flights"><span>Packages</span></a></li>
                            <li class="nav-item menu-link"><a class="nav-link" href="/coming_soon" ><img class="unselected_img" src="{{asset('images/cruise-icon.png')}}" alt="Cruise" data-tab="/coming_soon"><img class="selected_img" src="{{asset('images/cruise-icon.png')}}" alt="Cruise"><span>Cruise</span></a></li> -->
                            <li>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownLangButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-language"></i> {{ app()->getLocale() }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item change-locale" data-lang="en" href="{{ url('locale/en') }}">English</a>
                                        <a class="dropdown-item change-locale" data-lang="hn" href="{{ url('locale/hi') }}">हिंदी</a>
                                        <a class="dropdown-item change-locale" data-lang="heb" href="{{ url('locale/heb') }}">עִברִית</a>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <div class="right_menu">

                            <ul class="navbar-nav list-inline">
                                @guest
                                @else
                                <li>
                                    <a class="dropdown-item" href="/{{Auth::user()->role}}/wallet"><i class="fa fa-credit-card" aria-hidden="true"></i> {{ __('labels.wallet') }} ( {{ wallet_blance()['currency'] }} {{ number_format(wallet_blance()['amount'],2) }} )</a>
                                </li>
                                @endguest
                                @guest
                                <li class="nav-item dropdown my_account_menu">
                                    <button type="button" class="nav-link dropdown-toggle myaccount" id="moreDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('labels.create_account') }} <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                                    <div class="dropdown-menu" aria-labelledby="moreDropdown">
                                        <a class="dropdown-item" href="/login"><i class="fa fa-caret-right" aria-hidden="true"></i> {{ __('labels.login') }}</a>
                                        <a class="dropdown-item" href="/register"><i class="fa fa-caret-right" aria-hidden="true"></i> {{ __('labels.register') }}</a>
                                        <!--<a class="dropdown-item" href="/lottery"><i class="fa fa-caret-right" aria-hidden="true"></i>Lottery Registration</a>-->
                                        <!-- <a class="dropdown-item" href="/register/affiliate"><i class="fa fa-caret-right" aria-hidden="true"></i> {{ __('labels.agent_register') }}</a> -->
                                    </div>
                                </li>
                                @else
                                <li class="nav-item dropdown my_account_menu">
                                    <button type="button" class="nav-link dropdown-toggle myaccount" id="moreDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ __('labels.my_account') }} <i class="fa fa-angle-down" aria-hidden="true"></i></button>
                                    <div class="dropdown-menu" aria-labelledby="moreDropdown">
                                        @if(Auth::user()->role == 'agent')
                                            <a class="dropdown-item" href="/{{Auth::user()->role}}/settings"><i class="fa fa-caret-right"    aria-hidden="true"></i> {{Auth::user()->name}}</a>
                                        @else
                                            <a class="dropdown-item" href="/{{Auth::user()->role}}/profile"><i class="fa fa-caret-right" aria-hidden="true"></i> {{Auth::user()->name}}</a>
                                        @endif
                                        @if(Auth::user()->role == 'agent')
                                            <a class="dropdown-item" href="/agent/dashboard"><i class="fa fa-caret-right" aria-hidden="true"></i> {{ __('labels.dashboard') }}</a>
                                        @endif
                                        <a class="dropdown-item" href="/user/bookings"><i class="fa fa-caret-right" aria-hidden="true"></i> {{ __('labels.my_bookings') }}</a>
                                        <a class="dropdown-item" href="/{{Auth::user()->role}}/password/change"><i class="fa fa-caret-right" aria-hidden="true"></i> {{ __('labels.change_password') }}</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();
                                                signOut();"><i class="fa fa-caret-right" aria-hidden="true"></i> {{ __('labels.logout') }}</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                @endguest
                            </ul>
                        </div>
                    </div>
            </nav>
        </div>
    </header>
    @yield('content')
    <footer class="main_footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer_inner_data">
                        <h3>{{ __('labels.imp_links') }}</h3>
                        <ul class="list-inline">
                            <li><a href="/privacy-policy" target="_blank"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ __('labels.privacy_policy') }}</a></li>
                            <!--li><a href="/refund-policy" target="_blank"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ __('labels.refund_policy') }}</a></li-->
                            <li><a href="/terms-conditions" target="_blank"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ __('labels.terms_condition') }}</a></li>
                            <li><a href="/register"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ __('labels.create_account') }}</a></li>
                        </ul>
                        <!--  <ul class="list-inline">
                             >
                         </ul> -->
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer_inner_data">
                        <h3>{{ __('labels.about_site') }}</h3>
                        <ul class="list-inline">
                            <li><a href="/about-us"><i class="fa fa-angle-right" aria-hidden="true"></i>{{ __('labels.about_us') }}</a></li>
                            <li><a href="/register/affiliate"><i class="fa fa-angle-right" aria-hidden="true"></i> {{ __('labels.agent_register') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer_inner_data">
                        <h3>{{ __('labels.address') }}</h3>
                        <ul class="list-inline">
                            <!---<li>
                                <i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:+919877670485">+91-9877670485</a>
                            </li>-->
                            <li>
                                <i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:support@tripheist.com">support@tripheist.com</a>
                            </li>
                            @if(Session::get('locale') == 'heb')
                            <li>
                                <i class="fa fa-home" aria-hidden="true"></i> <a href="javascript:void(0);" >ד' עמק חפר 7/10 אשקלון</a>
                            </li>
                            <li>
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                    <a href="tel:050-9259966">050-9259966</a>
                            </li>
                            @else
                            <li>
                                <i class="fa fa-home" aria-hidden="true"></i> <a href="https://goo.gl/maps/LGZUPg3KkKhjGbsb8" target="_blank">SCO 136, First Floor</li></a>
                            </li>
                            <li>
                                <a href="https://goo.gl/maps/LGZUPg3KkKhjGbsb8" target="_blank">Mansa Devi Complex, Sector 5</a>
                            </li>
                            <li>
                                <a href="https://goo.gl/maps/LGZUPg3KkKhjGbsb8" target="_blank">Panchkula, 134114</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="bottom_footer">
                        <p class="copyright_data">{{ __('labels.copyright') }}</p>
                        <ul class="footer_social">
                            <li>{{ __('labels.keep_touch') }}: </li>
                            <li><a href="https://www.facebook.com/tripheist" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <!-- <li><a href="https://twitter.com/heist_trip" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li> -->
                            <li><a href="https://www.instagram.com/tripheistofficial/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="lotaryModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" >
                    <div class="modal-header" style="border-bottom: none;">
                        <h5 class="modal-title" id="exampleModalLabel">Lottery System</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="/lottery">
                            <div style="height: 420px;"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div id="loadingInProgress" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: transparent;border:none;">
                    <div class="modal-body text-center">
                        <img src="{{ asset('/images/flight-loader.gif') }}"   class="img-responsive" />
                        <h2 style="color:#fff;"><strong>Hold Tight</strong></h2>
                        <h3 style="color:#fff;">We are searching the best suitable <br/>and lowest price booking.</h3>        
                    </div>
                </div>
            </div>
        </div>
        <div id="bookingInProgress" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: transparent;border:none;">
                    <div class="modal-body text-center">
                        <img src="{{ asset('/images/flight-loader.gif') }}"   class="img-responsive" />
                        <h2 style="color:#fff;"><strong>Please wait</strong></h2>
                        <h3 style="color:#fff;">While we process your booking.</h3>        
                    </div>
                </div>
            </div>
        </div>
        @if (!request()->cookie('accept-cookie')) 
       <!-- <div class="cookies-block" style="position:fixed;background:#fd7e14;bottom:0px;width:100%;padding:5px;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <p  style="color:#fff;"><strong>Website uses cookies</strong></p>
                        <p style="color:#fff;">We use cookies to improve your experience and deliver personalised content. By using this website, you agree to our <a href="/privacy-policy">Cookie Policy</a>.</p>
                    </div>
                    <div class="col-lg-2 text-center">
                        <button class="btn btn-dark btn-sm okay-btn" >OK</button>
                    </div>
                </div>
            </div>
        </div>-->
        @endif
    </footer>
    <button onclick="topFunction()" id="goBackTop" title="Go to top"><i class="fa fa-lg fa-chevron-up"></i></button>
    <!-- <a href="javascript:void(0);" title="Support" id="supportBtn" data-toggle="modal" data-target="#support-modal"><i class="fa fa-comments-o"></i></a> -->
    <div class="modal" tabindex="-1" role="dialog" id="support-modal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">שירות לקוחות </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>טלפון שירות לקוחות בשפה העברית 050-9259966</p>
            <p> ימי ראשון עד חמישי  07:00-23:00</p>
            <p> יום שישי 07:00-17:00</p>
          </div>
        </div>
      </div>
    </div>
    <a href="javascript:void(0);" class="CustomerSupport" id="supportBtn" data-toggle="modal" data-target="#support-modal" >שירות לקוחות </a>
    <script type="text/javascript">
//        function googleTranslateElementInit() {
//            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
//        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- <script type="text/javascript" src="https://js.stripe.com/v2/"></script> -->
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js')}}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('js/select2.min.js')}}"></script>
    <script src="{{asset('js/parsley.js')}}"></script>
    <script src="{{asset('js/simpleLightbox.js')}}"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="{{ asset('js/razor-pay.js')}}"></script>

    <script id="customScript" src="{{ asset('js/custom.js')}}"></script>
    
    <script src="{{ asset('js/bootstrap4-toggle.min.js')}}"></script>


    <!-- angularjs -->
    <script type="text/javascript" src="{{ asset('js/angular/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/angular-sanitize.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/hotel.app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/flight-hotel.app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/flight.app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/cab.app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/activity.app.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/img-lazy.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/angular/ng-infinite-scroll.js') }}"></script>

    <script type="text/javascript" src="{{ asset('js/angular/hotel.static.app.js')}}"></script>
    <!-- ClickDesk Live Chat Service for websites -->
    <script type='text/javascript'>
        
        var uCountry = getCookie('th_country');
        var selectCountry = '';
        if(uCountry && uCountry !='') {
            try {
                var country = JSON.parse(getCookie('th_country'));
                selectCountry = country.countryCode;
            } catch (error) {
                selectCountry = '';
            }
        }

        if(selectCountry !== 'IL') {
            // var _glc = _glc || [];
            // _glc.push('all_ag9zfmNsaWNrZGVza2NoYXRyEgsSBXVzZXJzGICAiLuO0MILDA');
            // var glcpath = (('https:' == document.location.protocol) ? 'https://my.clickdesk.com/clickdesk-ui/browser/' : 'http://my.clickdesk.com/clickdesk-ui/browser/');
            // var glcp = (('https:' == document.location.protocol) ? 'https://' : 'http://');
            // var glcspt = document.createElement('script');
            // glcspt.type = 'text/javascript';
            // glcspt.async = true;
            // glcspt.src = glcpath + 'livechat-cloud-new.js';
            // var s = document.getElementsByTagName('script')[0];
            // s.parentNode.insertBefore(glcspt, s);
        } else {
            $('#supportBtn').show();
        }
    </script>
    <!-- End of ClickDesk -->

    <script>




    </script>


    <!-- Load the JS SDK asynchronously -->
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
            //console.log('statusChangeCallback');
            //console.log(response);  
            $('#login-status').hide();                 // The current login status of the person.
            if (response.status === 'connected') {   // Logged into your webpage and Facebook.
                testAPI();
            } else {                                 // Not logged into your webpage or we are unable to tell.
                //document.getElementById('status').innerHTML = 'Please log ' +
                //'into this webpage.';
                $('#login-status').show();
                $('#login-status').html('Somthing went wrong, please try again later');
            }
        }


        function checkLoginState() {               // Called when a person is finished with the Login Button.
            FB.getLoginStatus(function (response) {   // See the onlogin handler
                statusChangeCallback(response);
            });
        }


        window.fbAsyncInit = function () {
            FB.init({
                appId: '703033047049864',
                cookie: true, // Enable cookies to allow the server to access the session.
                xfbml: true, // Parse social plugins on this webpage.
                version: 'v9.0'           // Use this Graph API version for this call.
            });


            //FB.getLoginStatus(function(response) {   // Called after the JS SDK has been initialized.
            // statusChangeCallback(response);        // Returns the login status.
            // });
        };

        function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
            //console.log('Welcome!  Fetching your information.... ');
            FB.api('/me', function (response) {
                //console.log('Successful login for: ', response);
                //call the database API and create a new user
                $.ajax({
                    url: '/api/fb-login',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {_csrf: $('meta[name="csrf-token"]').attr('content'), data: response},
                    success: function (user) {
                        if (user && user.success) {
                            window.location.href = '/user/profile?email=' + user.force_email_change;
                        } else {
                            $('#login-status').show();
                            $('#login-status').html(user.message);
                        }
                    },
                    error: function (error) {
                        console.log('error in request ', error);
                        $('#login-status').show();
                        $('#login-status').html('Somthing went wrong, please try again later');
                    }
                });
                //  document.getElementById('status').innerHTML =
                //   'Thanks for logging in, ' + response.name + '!';
            });
        }

    </script>

    <script src="https://apis.google.com/js/platform.js?onload=onLoad" async defer></script>
    <script type="text/javascript">
        function onSignIn(googleUser) {
            var profile = googleUser.getBasicProfile();
            var profileData = {id: profile.getId(), email: profile.getEmail(), name: profile.getName(), picture: profile.getImageUrl()};
            $.ajax({
                url: '/api/g-login',
                type: 'POST',
                dataType: 'JSON',
                data: {_csrf: $('meta[name="csrf-token"]').attr('content'), data: profileData},
                success: function (user) {
                    if (user && user.success) {
                        window.location.href = '/user/profile?email=' + user.force_email_change;
                    } else {
                        $('#login-status').show();
                        $('#login-status').html(user.message);
                    }
                },
                error: function (error) {
                    console.log('error in request ', error);
                    $('#login-status').show();
                    $('#login-status').html('Somthing went wrong, please try again later');
                }
            });
        }

        function onLoad() {
            gapi.load('auth2', function () {
                gapi.auth2.init();
            });
        }

        function signOut() {
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(function () {
                console.log('User signed out.');
            });
        }
    </script>
    <script>
//Get the button
        var mybutton = document.getElementById("goBackTop");

// When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

// When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
   <!-- <script>
        $(document).on('click', '#footer .cookies-block button', function (e) {
            e.preventDefault();
            $.ajax({
                method: 'POST',
                url: '/cookie',
                data: {
                    'name': 'accept-cookie',
                    'value': true
                },
                success: function (response) {
                    $('#footer').find('.cookies-block').hide();
                },
                error: function (error) {
                    alert('Error: Please refresh the page');
                },
            });
        });
</script>-->


<script>
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
        $(document).ready(function() {
            //e.preventDefault();
            var home = $('#isHome').val();
            $.ajax({
                method: 'POST',
                url: '/api/set-country',
                success: function (response) {
                    if(home == '1') {
                       $('#f-origin').html('<option value="' + response.cityCode + '">'+ response.cityName +'</option>');
                       $('#from-city').val(response.cityName);
                       $('#from-city-text').html(response.cityName + ' (' + response.cityCode + ') ' + response.countryCode);

                       $('#f-origin-fh').html('<option value="' + response.cityCode + '">'+ response.cityName +'</option>');
                       $('#from-city-fh').val(response.cityName);
                       // $('#from-city-text').html(response.cityName + ' (' + response.cityCode + ') ' + response.countryCode);
                   }
                },
                error: function (error) {
                   
                },
            });
        });
    </script>
</body>
</html>
