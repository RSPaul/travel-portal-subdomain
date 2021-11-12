@extends('layouts.app-agent-header')
@section('content') 
<div class="app-content content chat-application">
   <div class="content-overlay"></div>
   <div class="header-navbar-shadow"></div>
   <div class="content-area-wrapper">
      <div class="sidebar-left">
         <div class="sidebar">
            <div class="chat-profile-sidebar">
               <header class="chat-profile-header">
                  <span class="close-icon">
                  <i data-feather="x"></i>
                  </span>
                  <div class="header-profile-sidebar">
                     <div class="avatar box-shadow-1 avatar-xl avatar-border">
                        <img src="/uploads/profiles/{{ $user->picture }}" alt="user_avatar" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';" />
                        <span class="avatar-status-online avatar-status-xl"></span>
                     </div>
                     <h4 class="chat-user-name">John Doe</h4>
                     <span class="user-post">Admin</span>
                  </div>
               </header>
               <div class="profile-sidebar-area">
                  <h6 class="section-label mb-1">About</h6>
                  <div class="about-user">
                     <textarea data-length="120" class="form-control char-textarea" id="textarea-counter" rows="5" placeholder="About User">{{ $agent->bio }}</textarea>
                  </div>
                  <h6 class="section-label mb-1 mt-3">Status</h6>
                  <ul class="list-unstyled user-status">
                     <li class="pb-1">
                        <div class="custom-control custom-control-success custom-radio">
                           <input type="radio" id="activeStatusRadio" name="userStatus" class="custom-control-input" value="online" checked />
                           <label class="custom-control-label ml-25" for="activeStatusRadio">Active</label>
                        </div>
                     </li>
                     <li class="pb-1">
                        <div class="custom-control custom-control-danger custom-radio">
                           <input type="radio" id="dndStatusRadio" name="userStatus" class="custom-control-input" value="busy" />
                           <label class="custom-control-label ml-25" for="dndStatusRadio">Do Not Disturb</label>
                        </div>
                     </li>
                     <li class="pb-1">
                        <div class="custom-control custom-control-warning custom-radio">
                           <input type="radio" id="awayStatusRadio" name="userStatus" class="custom-control-input" value="away" />
                           <label class="custom-control-label ml-25" for="awayStatusRadio">Away</label>
                        </div>
                     </li>
                     <li class="pb-1">
                        <div class="custom-control custom-control-secondary custom-radio">
                           <input type="radio" id="offlineStatusRadio" name="userStatus" class="custom-control-input" value="offline" />
                           <label class="custom-control-label ml-25" for="offlineStatusRadio">Offline</label>
                        </div>
                     </li>
                  </ul>
                  <!--/ To set user status -->
                  <!-- User settings -->
                  <h6 class="section-label mb-1 mt-2">Settings</h6>
                  <ul class="list-unstyled">
                     <li class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center">
                           <i data-feather="check-square" class="mr-75 font-medium-3"></i>
                           <span class="align-middle">Two-step Verification</span>
                        </div>
                        <div class="custom-control custom-switch mr-0">
                           <input type="checkbox" class="custom-control-input" id="customSwitch1" checked />
                           <label class="custom-control-label" for="customSwitch1"></label>
                        </div>
                     </li>
                     <li class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center">
                           <i data-feather="bell" class="mr-75 font-medium-3"></i>
                           <span class="align-middle">Notification</span>
                        </div>
                        <div class="custom-control custom-switch mr-0">
                           <input type="checkbox" class="custom-control-input" id="customSwitch2" />
                           <label class="custom-control-label" for="customSwitch2"></label>
                        </div>
                     </li>
                     <li class="mb-1 d-flex align-items-center cursor-pointer">
                        <i data-feather="user" class="mr-75 font-medium-3"></i>
                        <span class="align-middle">Invite Friends</span>
                     </li>
                     <li class="d-flex align-items-center cursor-pointer">
                        <i data-feather="trash" class="mr-75 font-medium-3"></i>
                        <span class="align-middle">Delete Account</span>
                     </li>
                  </ul>
                  <!--/ User settings -->
                  <!-- Logout Button -->
                  <div class="mt-3">
                     <button class="btn btn-primary">
                     <span>Logout</span>
                     </button>
                  </div>
                  <!--/ Logout Button -->
               </div>
               <!-- User Details end -->
            </div>
            <!--/ Admin user profile area -->
            <!-- Chat Sidebar area -->
            <div class="sidebar-content">
               <span class="sidebar-close-icon">
               <i data-feather="x"></i>
               </span>
               <!-- Sidebar header start -->
               <div class="chat-fixed-search">
                  <div class="d-flex align-items-center w-100">
                     <div class="sidebar-profile-toggle">
                        <div class="avatar avatar-border">
                           <img src="/uploads/profiles/{{ $user->picture }}" alt="user_avatar" height="42" width="42" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';" />
                           <span class="avatar-status-online"></span>
                        </div>
                     </div>
                     <div class="input-group input-group-merge ml-1 w-100">
                        <div class="input-group-prepend">
                           <span class="input-group-text round"><i data-feather="search" class="text-muted"></i></span>
                        </div>
                        <input type="text" class="form-control round" id="chat-search" placeholder="Search or start a new chat" aria-label="Search..." aria-describedby="chat-search" />
                     </div>
                  </div>
               </div>
               <!-- Sidebar header end -->
               <!-- Sidebar Users start -->
               <div id="users-list" class="chat-user-list-wrapper list-group">
                  <h4 class="chat-list-title">Chats</h4>
                  <ul class="chat-users-list chat-list media-list">
                    @foreach($chats as $chat)
                     <li class="select-chat-user" data-id="{{$chat['user_id']}}" data-image="@if($chat['picture'] != '') /uploads/profiles/{{ $chat['picture'] }} @endif" data-name="{{$chat['name']}}" data-phone="{{$chat['phone']}}">
                        <span class="avatar"><img src="/uploads/profiles/{{ $chat['picture'] }}" height="42" width="42" alt="Generic placeholder image" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';" />
                        <span class="avatar-status-offline"></span>
                        </span>
                        <div class="chat-info flex-grow-1">
                           <h5 class="mb-0">{{$chat['name']}}</h5>
                           <p class="card-text text-truncate">
                              {{$chat['message']}}
                           </p>
                        </div>
                        <div class="chat-meta text-nowrap">
                           <small class="float-right mb-25 chat-time">{{ date('h:i A', strtotime($chat['time']))}}</small>
                           @if($chat['unread'] > 0)
                           <span class="badge badge-danger badge-pill float-right">{{$chat['unread']}}</span>
                           @endif
                        </div>
                     </li>
                    @endforeach
                     <li class="no-results">
                        <h6 class="mb-0">No Chats Found</h6>
                     </li>
                  </ul>
                    @if(!empty($agents) && sizeof($agents) > 0)
                      <h4 class="chat-list-title">Contacts</h4>
                      <ul class="chat-users-list contact-list media-list">
                        @foreach($agents as $a)
                            @if($a->id != $user->id)
                             <li class="select-chat-user" data-id="{{$a->id}}" data-image="@if($a->picture != '') /uploads/profiles/{{ $a->picture }} @endif" data-name="{{$a->name}}" data-phone="{{$a->phone}}">
                                <span class="avatar"><img src="/uploads/profiles/{{ $a->picture }}" height="42" width="42" alt="Generic placeholder image" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';" />
                                </span>
                                <div class="chat-info">
                                   <h5 class="mb-0">{{$a->name}}</h5>
                                </div>
                             </li>
                            @endif
                        @endforeach
                    @endif

                     <li class="no-results">
                        <h6 class="mb-0">No Contacts Found</h6>
                     </li>
                  </ul>
               </div>
               <!-- Sidebar Users end -->
            </div>
            <!--/ Chat Sidebar area -->
         </div>
      </div>
      <div class="content-right">
         <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
               <div class="body-content-overlay"></div>
               <!-- Main chat area -->
               <section class="chat-app-window">
                  <!-- To load Conversation -->
                  <div class="start-chat-area">
                     <div class="mb-1 start-chat-icon">
                        <i data-feather="message-square"></i>
                     </div>
                     <h4 class="sidebar-toggle start-chat-text">Start Conversation</h4>
                  </div>
                  <!--/ To load Conversation -->
                  <!-- Active Chat -->
                  <div class="active-chat d-none">
                     <!-- Chat Header -->
                     <div class="chat-navbar">
                        <header class="chat-header">
                           <div class="d-flex align-items-center">
                              <div class="sidebar-toggle d-block d-lg-none mr-1">
                                 <i data-feather="menu" class="font-medium-5"></i>
                              </div>
                              <div class="avatar avatar-border user-profile-toggle m-0 mr-1">
                                 <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" id="select-user-pic" alt="avatar" height="36" width="36" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';"/>
                                 <span class="avatar-status-busy"></span>
                              </div>
                              <h6 class="mb-0" id="select-user-name">Kristopher Candy</h6>
                           </div>
                           <div class="d-flex align-items-center">
                              <a href="" id="doPCall">
                                <i data-feather="phone-call" class="cursor-pointer d-sm-block d-none font-medium-2 mr-1"></i>
                              </a>
                              <div class="dropdown">
                                 <button class="btn-icon btn btn-transparent hide-arrow btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i data-feather="more-vertical" id="chat-header-actions" class="font-medium-2"></i>
                                 </button>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="chat-header-actions">
                                    <a class="dropdown-item" href="javascript:void(0);" href="" id="viewProfile">View Contact</a>
                                    <!-- <a class="dropdown-item" href="javascript:void(0);">Mute Notifications</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Block Contact</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Clear Chat</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Report</a> -->
                                 </div>
                              </div>
                           </div>
                        </header>
                     </div>
                     <!--/ Chat Header -->
                     <!-- User Chat messages -->
                     <div class="user-chats">
                        <div class="chats">
                           <!-- <div class="chat">
                              <div class="chat-avatar">
                                 <span class="avatar box-shadow-1 cursor-pointer">
                                 <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="36" width="36" />
                                 </span>
                              </div>
                              <div class="chat-body">
                                 <div class="chat-content">
                                    <p>How can we help? We're here for you! 😄</p>
                                 </div>
                              </div>
                           </div>
                           <div class="chat chat-left">
                              <div class="chat-avatar">
                                 <span class="avatar box-shadow-1 cursor-pointer">
                                 <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="avatar" height="36" width="36" />
                                 </span>
                              </div>
                              <div class="chat-body">
                                 <div class="chat-content">
                                    <p>Hey John, I am looking for the best admin template.</p>
                                    <p>Could you please help me to find it out? 🤔</p>
                                 </div>
                                 <div class="chat-content">
                                    <p>It should be Bootstrap 4 compatible.</p>
                                 </div>
                              </div>
                           </div>
                           <div class="divider">
                              <div class="divider-text">Yesterday</div>
                           </div> -->
                        </div>
                     </div>
                     <!-- User Chat messages -->
                     <!-- Submit Chat form -->
                     <form class="chat-app-form" action="javascript:void(0);" onsubmit="enterChat();">
                        <div class="input-group input-group-merge mr-1 form-send-message">
                           <!-- <div class="input-group-prepend">
                              <span class="speech-to-text input-group-text"><i data-feather="mic" class="cursor-pointer"></i></span>
                           </div> -->
                           <input type="text" class="form-control message" name="message" placeholder="Type your message or use speech to text" autocomplete="off" />
                           <div class="input-group-append">
                              <span class="input-group-text">
                              <label for="attach-doc" class="attachment-icon mb-0">
                              <i data-feather="image" class="cursor-pointer lighten-2 text-secondary"></i>
                              <input type="file" id="attach-doc" hidden /> </label></span>
                           </div>
                        </div>
                        <input type="hidden" name="sender_id" id="sender_id" value="">
                        <button type="button" class="btn btn-primary send" onclick="enterChat();">
                        <i data-feather="send" class="d-lg-none"></i>
                        <span class="d-none d-lg-block">Send</span>
                        </button>
                     </form>
                     <!--/ Submit Chat form -->
                  </div>
                  <!--/ Active Chat -->
               </section>
               <!--/ Main chat area -->
               <!-- User Chat profile right area -->
               <div class="user-profile-sidebar">
                  <header class="user-profile-header">
                     <span class="close-icon">
                     <i data-feather="x"></i>
                     </span>
                     <!-- User Profile image with name -->
                     <div class="header-profile-sidebar">
                        <div class="avatar box-shadow-1 avatar-border avatar-xl">
                           <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="user_avatar" height="70" width="70" />
                           <span class="avatar-status-busy avatar-status-lg"></span>
                        </div>
                        <h4 class="chat-user-name">Kristopher Candy</h4>
                        <span class="user-post">UI/UX Designer 👩🏻‍💻</span>
                     </div>
                     <!--/ User Profile image with name -->
                  </header>
                  <div class="user-profile-sidebar-area">
                     <!-- About User -->
                     <h6 class="section-label mb-1">About</h6>
                     <p>Toffee caramels jelly-o tart gummi bears cake I love ice cream lollipop.</p>
                     <!-- About User -->
                     <!-- User's personal information -->
                     <div class="personal-info">
                        <h6 class="section-label mb-1 mt-3">Personal Information</h6>
                        <ul class="list-unstyled">
                           <li class="mb-1">
                              <i data-feather="mail" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">kristycandy@email.com</span>
                           </li>
                           <li class="mb-1">
                              <i data-feather="phone-call" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">+1(123) 456 - 7890</span>
                           </li>
                           <li>
                              <i data-feather="clock" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">Mon - Fri 10AM - 8PM</span>
                           </li>
                        </ul>
                     </div>
                     <!--/ User's personal information -->
                     <!-- User's Links -->
                     <div class="more-options">
                        <h6 class="section-label mb-1 mt-3">Options</h6>
                        <ul class="list-unstyled">
                           <li class="cursor-pointer mb-1">
                              <i data-feather="tag" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">Add Tag</span>
                           </li>
                           <li class="cursor-pointer mb-1">
                              <i data-feather="star" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">Important Contact</span>
                           </li>
                           <li class="cursor-pointer mb-1">
                              <i data-feather="image" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">Shared Media</span>
                           </li>
                           <li class="cursor-pointer mb-1">
                              <i data-feather="trash" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">Delete Contact</span>
                           </li>
                           <li class="cursor-pointer">
                              <i data-feather="slash" class="font-medium-2 mr-50"></i>
                              <span class="align-middle">Block Contact</span>
                           </li>
                        </ul>
                     </div>
                     <!--/ User's Links -->
                  </div>
               </div>
               <!--/ User Chat profile right area -->
            </div>
         </div>
      </div>
   </div>
</div>
@endsection