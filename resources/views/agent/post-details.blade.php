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
    <title>Tripheist - Post</title>
    @if(isset($meta_image))
        <meta property="og:title" content="{{ env('APP_NAME') }} - Post">
        <meta name="twitter:title" content="{{ env('APP_NAME') }} - Post">
        <meta property="og:image" content="{{$meta_image}}">
        <meta property="twitter:image" content="{{$meta_image}}">
        <meta property="og:url" content="{{Request::url()}}">
        <meta property="og:type" content="article">
        <meta property="og:description" content="{{ env('APP_NAME') }} - Post">
        <meta property="fb:app_id" content="703033047049864">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css') }}">
    <!-- BEGIN: Vendor CSS-->
   <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/vendors/css/vendors.min.css') }}">
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
    <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/core/menu/menu-types/horizontal-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/pages/page-blog.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('/css/agent/style.css') }}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="horizontal-layout horizontal-menu content-detached-right-sidebar navbar-floating footer-static  " data-open="hover" data-menu="horizontal-menu" data-col="content-detached-right-sidebar">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar-expand-lg navbar navbar-fixed align-items-center navbar-shadow navbar-brand-center" data-nav="brand-center">
        <div class="navbar-header d-xl-block d-none">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand" href="/agent/dashboard">
                     <span class="brand-logo-no-login">
                        <img src="{{asset('/images/logo.png')}}">
                     </span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="javascript:void(0);"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ml-auto">
                
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
                <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon" data-feather="search"></i></a>
                    <div class="search-input">
                        <div class="search-input-icon"><i data-feather="search"></i></div>
                        <input class="form-control input" type="text" placeholder="Explore Vuexy..." tabindex="-1" data-search="search">
                        <div class="search-input-close"><i data-feather="x"></i></div>
                        <ul class="search-list search-list-main"></ul>
                    </div>
                </li>

           
            </ul>
        </div>
    </nav>

    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->

    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content no-login">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow no-login"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        
                    </div>
                </div>
                
            </div>
            <div class="content-detached content-left">
                <div class="content-body">
                    <!-- Blog Detail -->
                    <div class="blog-detail-wrapper">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-start align-items-center mb-1">
                                            <!-- avatar -->
                                            <div class="avatar mr-1">
                                                <img src="/uploads/profiles/{{ $user['picture'] }}" alt="avatar img" height="50" width="50" onerror="this.onerror=null;this.src='https://via.placeholder.com/50?text=No Image';"/>
                                            </div>
                                            <!--/ avatar -->
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">{{ $user['name'] }}</h6>
                                            </div>
                                        </div>
                                        <h5 class="mb-75">About</h5>
                                        <p class="card-text">
                                            {{ $agent['bio'] }}
                                        </p>
                                        <div class="mt-2">
                                            <h5 class="mb-75">Joined:</h5>
                                            <p class="card-text">{{date('l, F d Y', strtotime($user['created_at'])) }}</p>
                                        </div>
                                        <div class="mt-2">
                                            <h5 class="mb-75">Lives:</h5>
                                            <p class="card-text">{{ $user['country'] }}</p>
                                        </div>
                                        <div class="mt-2">
                                            <h5 class="mb-75">Email:</h5>
                                            <p class="card-text"><a href="mailto:{{ $user['email'] }}">{{ $user['email'] }}</a></p>
                                        </div>
                                        <div class="mt-2">
                                            <h5 class="mb-50">Website:</h5>
                                            <p class="card-text mb-0"><a target="_blank" href="{{ $agent['website_url'] }}">{{ $agent['website_url'] }}</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Blog -->
                            <div class="col-md-9 col-lg-9 col-sm-12">
                                <div class="card">
                                    @if($post['post_type'] == 'article_image')
                                        @if(strpos($post['post_media'], 'http') !== false)
                                            <img src="{{$post['post_media']}}" class="img-fluid card-img-top" alt="Blog Detail Pic" />
                                        @else
                                            <img src="/uploads/posts/{{ $post['post_media'] }}" class="img-fluid card-img-top" alt="Blog Detail Pic" />
                                        @endif
                                    @endif
                                    @if($post['post_type'] == 'article_video')
                                        <iframe src="{{ $post['post_media'] }}" class="img-fluid card-img-top" alt="Blog Detail Pic"></iframe>
                                    @endif
                                    <div class="card-body">
                                        <!-- <h4 class="card-title">
                                            {!!$post['post_content'] !!}
                                        </h4> -->
                                        <div class="media">
                                            <div class="avatar mr-50">
                                                <img src="/uploads/profiles/{{ $user['picture'] }}" alt="Avatar" width="24" height="24" onerror="this.onerror=null;this.src='https://via.placeholder.com/50?text=No Image';"/>
                                            </div>
                                            <div class="media-body">
                                                <small class="text-muted mr-25">by</small>
                                                <small><a href="javascript:void(0);" class="text-body">{{$user['name']}}</a></small>
                                                <span class="text-muted ml-50 mr-25">|</span>
                                                <small class="text-muted">{{date('l, F d Y h:i:s', strtotime($post['created_at'])) }}</small>
                                            </div>
                                        </div>
                                        <!-- <div class="my-1 py-25">
                                            <a href="javascript:void(0);">
                                                <div class="badge badge-pill badge-light-danger mr-50">Gaming</div>
                                            </a>
                                            <a href="javascript:void(0);">
                                                <div class="badge badge-pill badge-light-warning">Video</div>
                                            </a>
                                        </div> -->
                                        <br>
                                        <p class="card-text mb-2">
                                            {!!$post['post_content'] !!}
                                        </p>

                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center mr-1">
                                                    <a href="/agent/profile" class="mr-50">
                                                        <i class="fa fa-heart font-medium-5 text-body align-middle"></i>
                                                    </a>
                                                    <a href="/agent/profile">
                                                        <div class="text-body align-middle">{{$likes}}</div>
                                                    </a>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="/agent/profile" class="mr-50">
                                                        <i  class="fa fa-comment font-medium-5 text-body align-middle"></i>
                                                    </a>
                                                    <a href="/agent/profile">
                                                        <div class="text-body align-middle">{{sizeof($comments)}}</div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Blog -->

                            <!-- Blog Comment -->
                            <div class="col-md-3 col-lg-3 col-sm-12">
                            </div>
                            <div class="col-md-9 col-lg-9 col-sm-12 mt-1" id="blogComment">
                                <h6 class="section-label mt-25">Comments</h6>
                                <div class="card">
                                    <div class="card-body">
                                    @foreach($comments as $comment)
                                        <div class="media">
                                            <div class="avatar mr-75">
                                                <img src="/uploads/profiles/{{ $comment['picture'] }}" width="38" height="38" alt="Avatar" />
                                            </div>
                                            <div class="media-body">
                                                <h6 class="font-weight-bolder mb-25">{{ $comment['name'] }}</h6>
                                                <p class="card-text">{{date('l, F d Y h:i:s', strtotime($comment['created_at'])) }}</p>
                                                <p class="card-text">
                                                    {{ $comment['comment'] }}
                                                </p>
                                            </div>
                                        </div>
                                        </br>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--/ Blog Comment -->

                          
                        </div>
                    </div>
                    <!--/ Blog Detail -->

                </div>
            </div>
            <div class="sidebar-detached sidebar-right">
                <div class="sidebar">
                    <div class="blog-sidebar my-2 my-lg-0">
                        <!-- Recent Posts -->
                        <div class="blog-recent-posts mt-3">
                            <h6 class="section-label">Recent Posts</h6>
                            <div class="mt-75">
                                @foreach($other_posts as $post)
                                    @if($post_id != $post['id'])
                                    <div class="media mb-2">
                                        <a href="page-blog-detail.html" class="mr-2">
                                            @if($post['post_type'] == 'article_image')
                                                @if(strpos($post['post_media'], 'http') !== false)
                                                    <img class="rounded" src="{{$post['post_media']}}" width="100" height="70" alt="Recent Post Pic" />
                                                @else
                                                    <img src="/uploads/posts/{{ $post['post_media'] }}" class="rounded" width="100" height="70" alt="Recent Post Pic" />
                                                @endif
                                            @elseif($post['post_type'] == 'article_video')
                                                <iframe class="rounded" src="{{$post['post_media']}}" width="100" height="70" ></iframe>
                                            @else
                                                <img class="rounded" src="https://via.placeholder.com/50?text=No Image'" width="100" height="70" alt="Recent Post Pic" />
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <h6 class="blog-recent-post-title">
                                                <a href="/post/{{$post['id']}}">{!!$post['post_content'] !!}</a>
                                            </h6>
                                            <div class="text-muted mb-0">{{date('l, F d Y h:i:s', strtotime($post['created_at'])) }}</div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <!--/ Recent Posts -->
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <!-- <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021<a class="ml-25" href="https://1.envato.market/pixinvent_portfolio" target="_blank">Pixinvent</a><span class="d-none d-sm-inline-block">, All rights Reserved</span></span><span class="float-md-right d-none d-md-block">Hand-crafted & Made with<i data-feather="heart"></i></span></p>
    </footer> -->
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->


 
</body>
<!-- END: Body-->

</html>