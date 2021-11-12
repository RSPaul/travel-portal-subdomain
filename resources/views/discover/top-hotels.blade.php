@extends('layouts.app-header')
@section('content')   

<section class="bread-cums top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p><a href="/">Home</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <a href="/discover/more-countries">Top Destinations</a>&nbsp;<i class="fa fa-angle-double-right"></i> 
                    <span>{{$selected_country['Country']}}</span>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="top-cities-section mt-4">
    <div class="container">
        <h1 class="text-center section_title">Top Cities</h1>

        <div class="row">

            @foreach($countries as $key => $value)
            <a href="/hotels/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{$value['CityId']}}" target="_blank">
                <div class="col-md-4">
                    <div id="discover-cities">
                        <div class="listing-countries"> 
                            <a href="/hotels/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{$value['CityId']}}" target="_blank">
                                <img  src="/uploads/featured_cities/{{ $value['image'] }}" class="discover-img" onerror="this.src= ''; this.src='https://via.placeholder.com/150?text=Image%20Not%20Uploaded'"/> 
                            </a>
                            <br>
                            <p class="title_country"> 
                               <a href="/hotels/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{$value['CityId']}}" target="_blank"> {{ $value['CityName'] }} </a>
                            </p> 
                            <p class="hotel-count"> {{ number_format($value['count'],2) }} hotels </p>  
                            <!-- <form class="" method="GET" name="searchForm" id="searchRoomsFormMain_{{$value['CityId']}}" action="/hotels/{{$value['Country']}}/{{$value['CityName']}}/{{$value['CityId']}}" target="_blank" ></form> -->
                        </div>                  
                    </div>
                </div> 
            </a>
            @endforeach
        </div>

    </div>
</section>

<section class="top-hotels-section mt-4">
    <div class="container">
        <h1 class="text-center section_title">Top Hotels</h1>
        <div class="row">
            @foreach($countries as $key => $value)
            @if(isset($value['hotel']) && !empty($value['hotel']))
                <a href="/hotel/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{strtolower(str_replace(' ', '-', $value['hotel']['hotel_name']))}}/{{$value['hotel']['hotel_code']}}/0" target="_blank">
                    <div class="col-md-4">
                        <div id="discover-cities">
                            <div class="listing-countries"> 
                                <a href="/hotel/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{strtolower(str_replace(' ', '-', $value['hotel']['hotel_name']))}}/{{$value['hotel']['hotel_code']}}" target="_blank">
                                    <img  src="{{ $value['hotel']['hotel_images'][0] }}/0" class="discover-img" onerror="this.src= ''; this.src='https://via.placeholder.com/150?text=Image%20Not%20Uploaded'"/> 
                                </a>
                                <p class="title_country"> 
                                   <br>
                                   <a href="/hotel/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{strtolower(str_replace(' ', '-', $value['hotel']['hotel_name']))}}/{{$value['hotel']['hotel_code']}}/0" target="_blank"> {{$value['hotel']['hotel_name']}} </a>
                                </p> 
                            </div>
                            <!-- <form class="" method="GET" name="searchForm" id="searchRoomsFormHotel_{{$value['CityId']}}" action="/hotel/{{$value['Country']}}/{{$value['CityName']}}/{{$value['hotel']['hotel_name']}}/{{$value['hotel']['hotel_code']}}" target="_blank" ></form> -->
                        </div>
                    </div> 
                </a>
            @endif
            @endforeach
        </div>

    </div>
</section>

<section class="top-hotels-section mt-4">
    <div class="container">
        <h1 class="text-center section_title">Areas of interest or regions in the {{ $selected_country['Country'] }}</h1>
        <div class="row">
            @foreach($other_cities as $key => $value)
            <a href="/hotels/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{$value['CityId']}}" target="_blank" >
                <div class="col-md-3">
                    <div class="other-cities">
                        <p class="title_country"> 
                            <a href="/hotels/{{strtolower(str_replace(' ', '-', $value['Country']))}}/{{strtolower(str_replace(' ', '-', $value['CityName']))}}/{{$value['CityId']}}" target="_blank" >{{$value['CityName']}} </a>
                        </p> 
                        <p class="hotel-count"> {{ number_format($value['hotels'],0) }} hotels </p>  
                    </div>
                    <!-- <form class="" method="GET" name="searchForm" id="searchRoomsFormDiscover_{{$value['CityId']}}" action="/hotels/{{$value['Country']}}/{{$value['CityName']}}/{{$value['CityId']}}" target="_blank" ></form> -->
                </div> 
            </a>
            @endforeach
        </div>

    </div>
</section>
@endsection
