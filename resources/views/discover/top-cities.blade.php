@extends('layouts.app-header')
@section('content')   
<section class="bread-cums top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p><a href="/">Home</a>&nbsp;<i class="fa fa-angle-double-right"></i> <span>Top Destinations</span></p>   
            </div>
        </div>
    </div>
</section>

<section class="top-hotels-section mt-4">
    <div class="container">
        <h1 class="text-center section_title">Top Destinations</h1>
        <div class="row">
            @foreach($countries as $key => $value)
            @if(isset($value['Country']) && !empty($value['Country']))
                <div class="col-md-4">
                    <div class="discover-cities country-list">
                        <div class="listing-countries"> 
                            <a href="javascript:void(0);" >
                                <img  src="/uploads/featured_cities/{{ $value['image'] }}" class="discover-img" onerror="this.src= ''; this.src='https://via.placeholder.com/150?text=Image%20Not%20Uploaded'"/> 
                            </a>
                            <p class="country-name"> 
                               <a href="javascript:void(0);" > {{$value['Country']}} </a>
                            </p> 
                            <p> {{number_format($value['country_hotels'],0) }} hotels</p>

                            <p><b>Top Cities</b></p>
                            @foreach($value['top_cities'] as $city)
                                <p class="city-name">
                                    <a href="/hotels/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $city->CityName))}}/{{$city->CityId}}" target="_blank"> {{$city->CityName}} </a>
                                </p>
                                <p class="city-hotels">{{number_format($city->hotels,0)}} hotels</p>
                                
                            @endforeach

                            <p><b>Top Hotels</b></p>
                            @foreach($value['top_hotels'] as $hotel)
                                <p class="hotel-name">
                                    <a href="/hotel/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $hotel['city_name']))}}/{{strtolower(str_replace(' ', '-', $hotel['hotel_name']))}}/{{$hotel['hotel_code']}}/0" target="_blank"> {{$hotel['hotel_name']}} </a>
                                </p>
                            @endforeach

                        </div>
                    </div>
                </div> 
            @endif
            @endforeach
        </div>

    </div>
</section>

@endsection
