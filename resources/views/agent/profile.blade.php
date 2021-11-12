@extends('layouts.app-agent-header')
@section('content') 
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/agent/profile">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Profile
                                </li>
                                <input type="hidden" id="user_picture" value="{{$user['picture']}}">
                                <input type="hidden" id="user_name" value="{{$user['name']}}">
                                <input type="hidden" id="siteURL" value="{{ env('APP_URL') }}">
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div id="user-profile">
                <!-- profile header -->
                <div class="row">
                    <div class="col-12">
                        <div class="card profile-header mb-2">
                            <!-- profile cover photo -->
                            <img class="card-img-top profile-cover" src="/uploads/profiles/{{$agent->cover_pic}}" alt="User Profile Image" onerror="this.src=''; this.src='/images/agent/profile/user-uploads/timeline.jpg'" />
                            <!--/ profile cover photo -->
                            <button id="select-files" class="btn btn-outline-primary mb-1 cover-picture">
                                <i data-feather="file"></i>Change Cover
                            </button>
                            <input type="hidden" id="selectedCoverImage" value="">
                            <input name="upload" type="file" id="fileinput" accept="image/*"/>
                            <div class="position-relative">
                                <!-- profile picture -->
                                <div class="profile-img-container d-flex align-items-center">
                                    <div class="profile-img">
                                        <img src="/uploads/profiles/{{ $user->picture }}" class="rounded img-fluid" alt="Card image" onerror="this.onerror=null;this.src='https://via.placeholder.com/200?text=No Image';" />
                                    </div>
                                    <!-- profile title -->
                                    <div class="profile-title ml-3">
                                        <h2 class="text-white">{{ $user->name }}</h2>
                                        <p class="text-white">Affiliate</p>
                                    </div>
                                </div>
                            </div>

                            <!-- tabs pill -->
                            <div class="profile-header-nav">
                                <!-- navbar -->
                                <nav class="navbar navbar-expand-md navbar-light justify-content-end justify-content-md-between w-100">
                                    <button class="btn btn-icon navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <i data-feather="align-justify" class="font-medium-5"></i>
                                    </button>

                                    <!-- collapse  -->
                                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                        <div class="profile-tabs d-flex justify-content-between flex-wrap mt-1 mt-md-0">
                                            <ul class="nav nav-pills mb-0">
                                                <li class="nav-item">
                                                    <a class="nav-link font-weight-bold active" href="javascript:void(0)">
                                                        <span class="d-none d-md-block">Feed</span>
                                                        <i data-feather="rss" class="d-block d-md-none"></i>
                                                    </a>
                                                </li>
                                                <!-- <li class="nav-item">
                                                    <a class="nav-link font-weight-bold" href="javascript:void(0)">
                                                        <span class="d-none d-md-block">About</span>
                                                        <i data-feather="info" class="d-block d-md-none"></i>
                                                    </a>
                                                </li> -->
                                                <li class="nav-item">
                                                    <a class="nav-link font-weight-bold" href="javascript:void(0)">
                                                        <span class="d-none d-md-block">Photos</span>
                                                        <i data-feather="image" class="d-block d-md-none"></i>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link font-weight-bold" href="javascript:void(0)">
                                                        <span class="d-none d-md-block">Friends</span>
                                                        <i data-feather="users" class="d-block d-md-none"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!-- edit button -->
                                            <button data-toggle="modal" data-target="#createPostModal" class="btn btn-primary">
                                                <i data-feather="edit" class="d-block d-md-none"></i>
                                                <span class="font-weight-bold d-none d-md-block">Create Post</span>
                                            </button>
                                        </div>
                                    </div>
                                    <!--/ collapse  -->
                                </nav>
                                <!--/ navbar -->
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ profile header -->

                <!-- profile info section -->
                <section id="profile-info">
                    <div class="row">
                        <!-- left profile info section -->
                        <div class="col-lg-3 col-12 order-2 order-lg-1">
                            <!-- about -->
                            <div class="card">
                                <div class="card-body">
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
                                        <p class="card-text">{{ $user['email'] }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h5 class="mb-50">Website:</h5>
                                        <p class="card-text mb-0">{{ $agent['website_url'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <!--/ about -->

                            <!-- suggestion pages -->
                            <div class="card">
                                <div class="card-body profile-suggestion">
                                    <h5 class="mb-2">Suggested Pages</h5>
                                    <!-- user suggestions -->
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <div class="avatar mr-1">
                                            <img src="{{ asset('/images/agent/avatars/12-small.png')}}" alt="avatar img" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Peter Reed</h6>
                                            <small class="text-muted">Company</small>
                                        </div>
                                        <div class="profile-star ml-auto"><i data-feather="star" class="font-medium-3"></i></div>
                                    </div>
                                    <!-- user suggestions -->
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <div class="avatar mr-1">
                                            <img src="{{ asset('/images/agent/avatars/1-small.png')}}" alt="avatar" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Harriett Adkins</h6>
                                            <small class="text-muted">Company</small>
                                        </div>
                                        <div class="profile-star ml-auto"><i data-feather="star" class="font-medium-3"></i></div>
                                    </div>
                                    <!-- user suggestions -->
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <div class="avatar mr-1">
                                            <img src="{{ asset('/images/agent/avatars/10-small.png')}}" alt="avatar" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Juan Weaver</h6>
                                            <small class="text-muted">Company</small>
                                        </div>
                                        <div class="profile-star ml-auto"><i data-feather="star" class="font-medium-3"></i></div>
                                    </div>
                                    <!-- user suggestions -->
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <div class="avatar mr-1">
                                            <img src="{{ asset('/images/agent/avatars/3-small.png')}}" alt="avatar img" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Claudia Chandler</h6>
                                            <small class="text-muted">Company</small>
                                        </div>
                                        <div class="profile-star ml-auto"><i data-feather="star" class="font-medium-3"></i></div>
                                    </div>
                                    <!-- user suggestions -->
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <div class="avatar mr-1">
                                            <img src="{{ asset('/images/agent/avatars/5-small.png')}}" alt="avatar img" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Earl Briggs</h6>
                                            <small class="text-muted">Company</small>
                                        </div>
                                        <div class="profile-star ml-auto">
                                            <i data-feather="star" class="profile-favorite font-medium-3"></i>
                                        </div>
                                    </div>
                                    <!-- user suggestions -->
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="avatar mr-1">
                                            <img src="{{ asset('/images/agent/avatars/6-small.png')}}" alt="avatar img" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Jonathan Lyons</h6>
                                            <small class="text-muted">Beauty Store</small>
                                        </div>
                                        <div class="profile-star ml-auto"><i data-feather="star" class="font-medium-3"></i></div>
                                    </div>
                                </div>
                            </div>
                            <!--/ suggestion pages -->

                            <!-- twitter feed card -->
                            <div class="card">
                                <div class="card-body">
                                    <h5>Twitter Feeds</h5>
                                    <!-- twitter feed -->
                                    <div class="profile-twitter-feed mt-1">
                                        <div class="d-flex justify-content-start align-items-center mb-1">
                                            <div class="avatar mr-1">
                                                <img src="{{ asset('/images/agent/avatars/5-small.png')}}" alt="avatar img" height="40" width="40" />
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">Gertrude Stevens</h6>
                                                <a href="javascript:void(0)">
                                                    <small class="text-muted">@tiana59</small>
                                                    <i data-feather="check-circle"></i>
                                                </a>
                                            </div>
                                            <div class="profile-star ml-auto">
                                                <i data-feather="star" class="font-medium-3"></i>
                                            </div>
                                        </div>
                                        <p class="card-text mb-50">I love cookie chupa chups sweet tart apple pie ⭐️ chocolate bar.</p>
                                        <a href="javascript:void(0)">
                                            <small>#design #fasion</small>
                                        </a>
                                    </div>
                                    <!-- twitter feed -->
                                    <div class="profile-twitter-feed mt-2">
                                        <div class="d-flex justify-content-start align-items-center mb-1">
                                            <div class="avatar mr-1">
                                                <img src="{{ asset('/images/agent/avatars/12-small.png')}}" alt="avatar img" height="40" width="40" />
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">Lura Jones</h6>
                                                <a href="javascript:void(0)">
                                                    <small class="text-muted">@tiana59</small>
                                                    <i data-feather="check-circle"></i>
                                                </a>
                                            </div>
                                            <div class="profile-star ml-auto">
                                                <i data-feather="star" class="font-medium-3 profile-favorite"></i>
                                            </div>
                                        </div>
                                        <p class="card-text mb-50">Halvah I love powder jelly I love cheesecake cotton candy. 😍</p>
                                        <a href="javascript:void(0)">
                                            <small>#vuejs #code #coffeez</small>
                                        </a>
                                    </div>
                                    <!-- twitter feed -->
                                    <div class="profile-twitter-feed mt-2">
                                        <div class="d-flex justify-content-start align-items-center mb-1">
                                            <div class="avatar mr-1">
                                                <img src="{{ asset('/images/agent/avatars/1-small.png')}}" alt="avatar img" height="40" width="40" />
                                            </div>
                                            <div class="profile-user-info">
                                                <h6 class="mb-0">Norman Gross</h6>
                                                <a href="javascript:void(0)">
                                                    <small class="text-muted">@tiana59</small>
                                                    <i data-feather="check-circle"></i>
                                                </a>
                                            </div>
                                            <div class="profile-star ml-auto">
                                                <i data-feather="star" class="font-medium-3"></i>
                                            </div>
                                        </div>
                                        <p class="card-text mb-50">Candy jelly beans powder brownie biscuit. Jelly marzipan oat cake cake.</p>
                                        <a href="javascript:void(0)">
                                            <small>#sketch #uiux #figma</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--/ twitter feed card -->
                        </div>
                        <!--/ left profile info section -->

                        <!-- center profile info section -->
                        <div class="col-lg-6 col-12 order-1 order-lg-2">
                            @foreach($posts as $post_key => $post)
                            <!-- post starts -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-start align-items-center mb-1">
                                        <!-- avatar -->
                                        <div class="avatar mr-1">
                                            <a href="/agent/profile/{{strtolower(str_replace(' ', '-', $post['name']))}}/{{$post['userId']}}">
                                                <img src="/uploads/profiles/{{ $post['picture'] }}" alt="avatar img" height="50" width="50" onerror="this.onerror=null;this.src='https://via.placeholder.com/50?text=No Image';"/>
                                            </a>
                                        </div>
                                        <!--/ avatar -->
                                        <div class="profile-user-info">
                                            <a href="/agent/profile/{{strtolower(str_replace(' ', '-', $post['name']))}}/{{$post['userId']}}">
                                                <h6 class="mb-0">{{ $post['name'] }}</h6>
                                            </a>
                                            <small class="text-muted">{{date('l, F d Y h:i:s', strtotime($post['created_at'])) }}</small>
                                        </div>
                                    </div>
                                    <p class="card-text" id="post_content_{{$post['id']}}">
                                        {!!$post['post_content'] !!}
                                    </p>
                                    <!-- post img -->
                                    @if($post['post_type'] == 'article_image')
                                        @if(strpos($post['post_media'], 'http') !== false)
                                            <img class="img-fluid rounded mb-75" src="{{$post['post_media']}}" alt="avatar img" />
                                        @else
                                            <img class="img-fluid rounded mb-75" src="/uploads/posts/{{ $post['post_media'] }}" alt="avatar img" />
                                        @endif
                                    @endif
                                    <!--/ post img -->

                                    <!-- video -->
                                    @if($post['post_type'] == 'article_video')
                                        <iframe src="{{ $post['post_media'] }}" class="w-100 rounded border-0 height-250 mb-50"></iframe>
                                    @endif
                                    <!--/ video -->

                                    <!-- like share -->
                                    <div class="row d-flex justify-content-start align-items-center flex-wrap">
                                        <div class="col-sm-6 d-flex justify-content-between justify-content-sm-start mb-2">
                                            <a href="javascript:void(0)" class="d-flex align-items-center text-muted text-nowrap">
                                                @if($post['liked'] == '1')
                                                    <i class="fa fa-heart profile-likes font-medium-3 mr-50" data-id="{{ $post['id'] }}" data-likes="{{ sizeof($post['likes']) }}"></i>
                                                @else
                                                    <i class="fa fa-heart-o profile-likes font-medium-3 mr-50" data-id="{{ $post['id'] }}" data-likes="{{ sizeof($post['likes']) }}"></i>
                                                @endif
                                                <span id="like_count_{{ $post['id'] }}">{{sizeof($post['likes'])}}</span>
                                            </a>

                                            <!-- avatar group with tooltip -->
                                            <div class="d-flex align-items-center" id="post_likes_avatar_{{ $post['id'] }}">
                                                <div class="avatar-group ml-1">
                                                    @foreach($post['likes'] as $like_key => $like)
                                                        @if($like_key < 5)
                                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="{{$like['name']}}" class="avatar pull-up">
                                                                <img src="/uploads/profiles/{{ $like['picture'] }}" alt="Avatar" height="26" width="26"   onerror="this.onerror=null;this.src='https://via.placeholder.com/50?text=No Image';"/>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                @if(sizeof($post['likes']) > 6)
                                                    <a href="javascript:void(0)" class="text-muted text-nowrap ml-50">+{{ sizeof($post['likes']) - 6}} more</a>
                                                @endif
                                            </div>
                                            <!-- avatar group with tooltip -->
                                        </div>

                                        <!-- share and like count and icons -->
                                        <div class="col-sm-6 d-flex justify-content-between justify-content-sm-end align-items-center mb-2">
                                            <a href="javascript:void(0)" class="text-nowrap">
                                                <i data-feather="message-square" class="text-body font-medium-3 mr-50"></i>
                                                <span class="text-muted mr-1" id="comment_count_{{ $post['id'] }}">{{sizeof($post['comments'])}}</span>
                                            </a>

                                            <a href="javascript:void(0)" class="text-nowrap post-share" data-id="{{ $post['id'] }}">
                                                <i data-feather="share-2" class="text-body font-medium-3 mx-50"></i>
                                                <span class="text-muted">1.25k</span>
                                            </a>                                            
                                        </div>
                                        <!-- share and like count and icons -->
                                    </div>
                                    <div class="row d-flex justify-content-start align-items-center flex-wrap">
                                        <div class="col-sm-10 d-flex justify-content-between justify-content-sm-start mb-2">
                                        </div>
                                        <div class="col-sm-2 d-flex justify-content-between justify-content-sm-start mb-2 social-icons">
                                            <div class="share-post-icons" id="post_share_icon_{{ $post['id'] }}">
                                                <div class="row">
                                                    <a class="share-to" data-type="fb" data-id="{{ $post['id'] }}">
                                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="share-to" data-type="wp" data-id="{{ $post['id'] }}">
                                                        <i class="fa fa-whatsapp" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="share-to" data-type="tw" data-id="{{ $post['id'] }}">
                                                        <i class="fa fa-twitter" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- like share -->

                                    <!-- comments -->
                                    <div id="comments_{{ $post['id'] }}">
                                        @foreach($post['comments'] as $comment)
                                        <div class="d-flex align-items-start mb-1 single-comment">
                                            <div class="avatar mt-25 mr-75">
                                                <img src="/uploads/profiles/{{ $comment['picture'] }}" alt="Avatar" height="34" width="34" />
                                            </div>
                                            <div class="profile-user-info w-100">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-0">{{ $comment['name'] }}</h6>
                                                    <a href="javascript:void(0)">
                                                        <i class="fa fa-heart text-body font-medium-3"></i>
                                                        <span class="align-middle text-muted">34</span>
                                                    </a>
                                                </div>
                                                <small>{{ $comment['comment'] }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <!--/ comments -->

                                    <!-- comment box -->
                                    <form class="commentsForm" action="/api/agent/post/comment" id="commentForm_{{ $post['id'] }}" data-id="{{ $post['id'] }}">
                                        <fieldset class="form-label-group mb-75">
                                            <textarea class="form-control comment-text" name="comment_{{ $post['id'] }}" id="comment_{{ $post['id'] }}" rows="3" placeholder="Add Comment" data-id="{{ $post['id'] }}" required></textarea>
                                            <label for="label-textarea">Add Comment</label>
                                        </fieldset>
                                        <!--/ comment box -->
                                        <button type="submit" class="btn btn-sm btn-primary comment-submit" id="submit_form_{{ $post['id'] }}" data-id="{{ $post['id'] }}" disabled="true">Post Comment</button>
                                        <input type="hidden" name="post_id_{{ $post['id'] }}" id="post_id_{{ $post['id'] }}" value="{{ $post['id'] }}">
                                        <input type="hidden" name="clear_form" id="clear_form_{{ $post['id'] }}" value="true">
                                    </form>
                                </div>
                            </div>

                            @endforeach

                            @if(sizeof($posts) == 0)
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-text">
                                            <h3>There are no posts to show, please check again after some time.</h3>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            <!--/ post ends -->
                        </div>
                        <!--/ center profile info section -->

                        <!-- right profile info section -->
                        <div class="col-lg-3 col-12 order-3">
                            <!-- latest profile pictures -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-0">Latest Photos</h5>
                                    <div class="row">
                                        @foreach($photos as $photo)
                                        <div class="col-md-4 col-6 profile-latest-img">
                                            <a href="/post/{{$photo->id}}">
                                                @if(strpos($photo->post_media, 'http') !== false)
                                                    <img src="{{$photo->post_media}}" class="img-fluid rounded lat-pho" alt="avatar img" />
                                                @else
                                                    <img src="/uploads/posts/{{$photo->post_media}}" class="img-fluid rounded lat-pho" alt="avatar img" />
                                                @endif
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!--/ latest profile pictures -->

                            <!-- suggestion -->
                            <div class="card">
                                <div class="card-body">
                                    <h5>Suggestions</h5>
                                    <div class="d-flex justify-content-start align-items-center mt-2">
                                        <div class="avatar mr-75">
                                            <img src="{{ asset('/images/agent/portrait/small/avatar-s-9.jpg')}}" alt="avatar" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Peter Reed</h6>
                                            <small class="text-muted">6 Mutual Friends</small>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-icon btn-sm ml-auto">
                                            <i data-feather="user-plus"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-start align-items-center mt-1">
                                        <div class="avatar mr-75">
                                            <img src="{{ asset('/images/agent/portrait/small/avatar-s-6.jpg')}}" alt="avtar img holder" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Harriett Adkins</h6>
                                            <small class="text-muted">3 Mutual Friends</small>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm btn-icon ml-auto">
                                            <i data-feather="user-plus"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-start align-items-center mt-1">
                                        <div class="avatar mr-75">
                                            <img src="{{ asset('/images/agent/portrait/small/avatar-s-7.jpg')}}" alt="avatar" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Juan Weaver</h6>
                                            <small class="text-muted">1 Mutual Friends</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary btn-icon ml-auto">
                                            <i data-feather="user-plus"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-start align-items-center mt-1">
                                        <div class="avatar mr-75">
                                            <img src="{{ asset('/images/agent/portrait/small/avatar-s-8.jpg')}}" alt="avatar img" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Claudia Chandler</h6>
                                            <small class="text-muted">16 Mutual Friends</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary btn-icon ml-auto">
                                            <i data-feather="user-plus"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-start align-items-center mt-1">
                                        <div class="avatar mr-75">
                                            <img src="{{ asset('/images/agent/portrait/small/avatar-s-1.jpg')}}" alt="avatar img" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Earl Briggs</h6>
                                            <small class="text-muted">4 Mutual Friends</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary btn-icon ml-auto">
                                            <i data-feather="user-plus"></i>
                                        </button>
                                    </div>
                                    <div class="d-flex justify-content-start align-items-center mt-1">
                                        <div class="avatar mr-75">
                                            <img src="{{ asset('/images/agent/portrait/small/avatar-s-10.jpg')}}" alt="avatar img" height="40" width="40" />
                                        </div>
                                        <div class="profile-user-info">
                                            <h6 class="mb-0">Jonathan Lyons</h6>
                                            <small class="text-muted">25 Mutual Friends</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary btn-icon ml-auto">
                                            <i data-feather="user-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!--/ suggestion -->

                            <!-- polls card -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="mb-1">Polls</h5>
                                    <p class="card-text mb-0">Who is the best actor in Marvel Cinematic Universe?</p>

                                    <!-- polls -->
                                    <div class="profile-polls-info mt-2">
                                        <!-- custom radio -->
                                        <div class="d-flex justify-content-between">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="bestActorPoll1" name="bestActorPoll" class="custom-control-input" />
                                                <label class="custom-control-label" for="bestActorPoll1">RDJ</label>
                                            </div>
                                            <div class="text-right">82%</div>
                                        </div>
                                        <!--/ custom radio -->

                                        <!-- progressbar -->
                                        <div class="progress progress-bar-primary my-50">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="58" aria-valuemin="58" aria-valuemax="100" style="width: 82%"></div>
                                        </div>
                                        <!--/ progressbar -->

                                        <!-- avatar group with tooltip -->
                                        <div class="avatar-group my-1">
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Tonia Seabold" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-12.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Carissa Dolle" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-5.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Kelle Herrick" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-9.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Len Bregantini" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-10.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="John Doe" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-11.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                        </div>
                                        <!--/ avatar group with tooltip -->
                                    </div>

                                    <div class="profile-polls-info mt-2">
                                        <div class="d-flex justify-content-between">
                                            <!-- custom radio -->
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="bestActorPoll2" name="bestActorPoll" class="custom-control-input" />
                                                <label class="custom-control-label" for="bestActorPoll2">Chris Hemswort</label>
                                            </div>
                                            <!--/ custom radio -->
                                            <div class="text-right">67%</div>
                                        </div>
                                        <!-- progressbar -->
                                        <div class="progress progress-bar-primary my-50">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="16" aria-valuemin="16" aria-valuemax="100" style="width: 67%"></div>
                                        </div>
                                        <!--/ progressbar -->

                                        <!-- avatar group with tooltips -->
                                        <div class="avatar-group mt-1">
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Liliana Pecor" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-9.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Kasandra NaleVanko" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-1.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                            <div data-toggle="tooltip" data-popup="tooltip-custom" data-placement="bottom" data-original-title="Jonathan Lyons" class="avatar pull-up">
                                                <img src="{{ asset('/images/agent/portrait/small/avatar-s-8.jpg')}}" alt="Avatar" height="26" width="26" />
                                            </div>
                                        </div>
                                        <!--/ avatar group with tooltips-->
                                    </div>
                                    <!--/ polls -->
                                </div>
                            </div>
                            <!--/ polls card -->
                        </div>
                        <!--/ right profile info section -->
                    </div>

                    <!-- reload button -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-sm btn-primary block-element border-0 mb-1">Load More</button>
                        </div>
                    </div>
                    <!--/ reload button -->
                </section>
                <!--/ profile info section -->
            </div>

        </div>
    </div>
</div>

<div class="modal fade text-left modal-primary" id="createPostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel110" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="myModalLabel110">Create New Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/api/agent/post" class="formValidation" id="createPostForm">
                <div class="modal-body">
                    <label>Post Type </label>
                    <div class="form-group">
                        <select name="post_type" id="post_type"  class="form-control" required>
                            <option value="article">Article</option>
                            <option value="article_image">Article with Image</option>
                            <option value="article_video">Article with Video</option>
                        </select>
                        <div class="invalid-feedback">Please select post type.</div>
                    </div>

                    <label>Content </label>
                    <div class="form-group">
                        <textarea  required name="post_content" placeholder="Tell about what are you going to share" class="form-control editor" /></textarea>
                        <div class="invalid-feedback">Please enter post content.</div>
                    </div>

                    <div id="post-image" style="display: none;">
                        <label>Upload Image <span class="info-text">(1000 X 600 is recommended resultion for images.)</span></label>
                        <div class="form-group">
                            <input type="file" name="post_media" id="post_media" class="form-control">
                        </div>
                    </div>

                    <div id="post-video" style="display: none;">
                        <label>Video Link <span class="info-text">(Enter the video embed link)</span></label>
                        <div class="form-group">
                            <input type="url" name="post_media_link" id="post_media_link" class="form-control" placeholder="https://www.youtube.com/embed/EngW7tLk6R8">
                        </div>
                    </div>

                    <div class="form-group">
                        <img src="" width="150" height="150" class="post_media-preview" id="post_media-preview-img" style="display: none;">
                        <iframe src="" width="150" height="150" class="post_media-preview-vid" style="display: none;"></iframe>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="post_media_hidden" id="post_media_hidden" value="">
                    <input type="hidden" name="reload" id="reload" value="true">
                    <input type="submit" class="btn btn-primary" value="Post">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection