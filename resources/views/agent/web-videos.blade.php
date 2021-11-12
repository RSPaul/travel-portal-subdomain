@extends('layouts.app-agent-header')
@section('content') 
<div class="app-content content ecommerce-application">
   <div class="content-overlay"></div>
   <div class="header-navbar-shadow"></div>
   <div class="content-wrapper">
      <div class="content-header row">
         <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
               <div class="col-12">
                  <div class="breadcrumb-wrapper">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/agent/settings">Dashboard</a></li>
                        <li class="breadcrumb-item active">Webinar/Videos
                        </li>
                     </ol>
                  </div>
               </div>
            </div>
         </div>

      </div>
      <div class="">
         <div class="content-body">
            <!-- E-commerce Content Section Starts -->
            <section id="ecommerce-header">
               <div class="row">
                  <div class="col-sm-12">
                     <div class="ecommerce-header-items">
                        <div class="result-toggler">
                           <button class="navbar-toggler shop-sidebar-toggler" type="button" data-toggle="collapse">
                           <span class="navbar-toggler-icon d-block d-lg-none"><i data-feather="menu"></i></span>
                           </button>
                        </div>
                        <div class="view-options d-flex">
                           <!-- <div class="btn-group dropdown-sort">
                              <button type="button" class="btn btn-outline-primary dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="active-sorting">Featured</span>
                              </button>
                              <div class="dropdown-menu">
                                 <a class="dropdown-item" href="javascript:void(0);">Featured</a>
                                 <a class="dropdown-item" href="javascript:void(0);">Lowest</a>
                                 <a class="dropdown-item" href="javascript:void(0);">Highest</a>
                              </div>
                           </div> -->
                           <div class="btn-group btn-group-toggle" data-toggle="buttons">
                              <label class="btn btn-icon btn-outline-primary view-btn grid-view-btn">
                              <input type="radio" name="radio_options" id="radio_option1" checked />
                              <i data-feather="grid" class="font-medium-3"></i>
                              </label>
                              <label class="btn btn-icon btn-outline-primary view-btn list-view-btn">
                              <input type="radio" name="radio_options" id="radio_option2" />
                              <i data-feather="list" class="font-medium-3"></i>
                              </label>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- E-commerce Content Section Starts -->
            <!-- background Overlay when sidebar is shown  starts-->
            <div class="body-content-overlay"></div>
            <!-- background Overlay when sidebar is shown  ends-->
            <!-- E-commerce Search Bar Starts -->
            <section id="ecommerce-searchbar" class="ecommerce-searchbar">
               <div class="row mt-1">
                  <div class="col-sm-12">
                     <div class="input-group input-group-merge">
                        <input type="text" class="form-control search-product" id="shop-search" placeholder="Search Videos" aria-label="Search..." aria-describedby="shop-search" onkeyup="searcVideos()"/>
                        <div class="input-group-append">
                           <span class="input-group-text"><i data-feather="search" class="text-muted"></i></span>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <!-- E-commerce Search Bar Ends -->
            <!-- E-commerce Products Starts -->
            <section id="ecommerce-products" class="grid-view">
                @foreach($videos as $video)
                <div class="card ecommerce-card" data-title="{{$video->title}}">
                  <div class="item-img text-center">
                    <iframe src="{{$video->media_link}}"></iframe>
                  </div>
                  <div class="card-body">
                     <div class="item-wrapper">
                        <!-- <div class="item-rating">
                           <ul class="unstyled-list list-inline">
                              <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                              <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                              <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                              <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                              <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                           </ul>
                        </div> -->
                        <div>
                           <h6 class="item-price">{{date('l, F d Y', strtotime($video->created_at)) }}</h6>
                        </div>
                     </div>
                     <h6 class="item-name">
                        <a class="text-body" href="javascript:void(0);">{{$video->title}}</a>
                     </h6>
                     <p class="card-text item-description">
                        @if(strlen($video->description) > 150)
                            {{ substr($video->description, 0, 150) }}..
                        @else
                            {{$video->description}}
                        @endif
                     </p>
                  </div>
                  <div class="item-options text-center">
                     <div class="item-wrapper">
                        <div class="item-cost">
                           <h4 class="item-price">{{date('l, F d Y', strtotime($video->created_at)) }}</h4>
                        </div>
                     </div>
                     <a href="javascript:void(0)" class="btn btn-light btn-wishlist">
                     <i data-feather="heart"></i>
                     <span>Wishlist</span>
                     </a>
                     <a href="javascript:void(0)" class="btn btn-primary" data-toggle="modal" data-target="#video_{{$video->id}}">
                     <i class="fa fa-eye"></i>
                        <span class="add-to-cart">View Details</span>
                     </a>
                  </div>
                </div>

                <div class="modal fade text-left" id="video_{{$video->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel1">{{$video->title}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="card">
                                    <iframe src="{{$video->media_link}}" height="400px"></iframe>
                                </div>
                                <p>
                                    {{$video->description}}
                                </p>
                            </div>
                            <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Accept</button>
                            </div> -->
                        </div>
                    </div>
                </div>
                @endforeach
            </section>
            <!-- E-commerce Products Ends -->
            <!-- E-commerce Pagination Starts -->
            <!-- <section id="ecommerce-pagination">
               <div class="row">
                  <div class="col-sm-12">
                     <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mt-2">
                           <li class="page-item prev-item"><a class="page-link" href="javascript:void(0);"></a></li>
                           <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                           <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                           <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                           <li class="page-item" aria-current="page"><a class="page-link" href="javascript:void(0);">4</a></li>
                           <li class="page-item"><a class="page-link" href="javascript:void(0);">5</a></li>
                           <li class="page-item"><a class="page-link" href="javascript:void(0);">6</a></li>
                           <li class="page-item"><a class="page-link" href="javascript:void(0);">7</a></li>
                           <li class="page-item next-item"><a class="page-link" href="javascript:void(0);"></a></li>
                        </ul>
                     </nav>
                  </div>
               </div>
            </section> -->
            <!-- E-commerce Pagination Ends -->
         </div>
      </div>
   </div>
</div>
@endsection