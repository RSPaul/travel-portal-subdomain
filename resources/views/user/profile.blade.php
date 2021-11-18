@extends('layouts.app-header')

@section('content')

@if(isset($agent))
<section class="booking_lists_data profile_data_info">
  <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="profile_picture_data">
                <div class="row">
                    <div class="col-md-3 pr-0">
                        <img src="/uploads/profiles/{{ $user->picture }}" class="profile-pic" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';">
                    </div>
                    <div class="col-md-9 pl-0">
                        <h4>{{ $user->name }} <!-- <img src="https://www.countryflags.io/{{ $country_name['code'] }}/flat/32.png" class="flag-pic"> --></h4>
                        @if(isset($country_name))
                        <p>
                            {{ $country_name['name'] }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="profile_tabs_data">
      <nav class="bookings_menu_tab ">
       <div class="nav nav-tabs nav-fill" role="tablist">
          <a class="nav-item nav-link active" id="nav-fare-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
       </div>
      </nav>
      <div class="tab_profile_info">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile">
               
                <form class="profile_edits_profiles" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        @if($user->social_id != '')
                            <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
                        @else
                            <input type="text" name="email" class="form-control" value="{{ $user->email }}" readonly>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <select class="form-control" name="country">
                            @foreach($cities as $city)
                                <option value="{{ $city->code }}" @if($city->code == $user->country) selected='selected' @endif>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Profile Picture</label>
                        <input id="image" type="file" name="image">
                    </div>
                    <div class="profile_update_data">
                        <input type="submit" name="submit" value="Update" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        </div>
        </div>
  </div>
</section>
@else
<section id="cover" class="min-vh-100">
    <div id="cover-caption">
        <div class="container">
            <div class="row">
            	<div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form ">
            		<br><br>
            		@if (session('success'))
				        <div class="alert alert-success" role="alert" >
				            {{ session('success') }}
				        </div>
				    @endif
                    @if($showEmailMessage) 
                        <div class="alert alert-success" role="alert" >
                            <p>Please update your email address.</p>
                        </div>
                    @endif
                    <h3 class="clr-blk">Update Profile</h3>
                    <div class="px-2">
                        <form action="" method="POST">
		        		@csrf
		        		<div class="form-group clr-blk">
		        			<label>Name</label>
		        			<input type="text" name="name" class="form-control" value="{{ $user->name }}">
		        		</div>
		        		<div class="form-group clr-blk">
		        			<label>Email</label>
                            @if($user->social_id != '')
                                @if(filter_var($user->email, FILTER_VALIDATE_EMAIL))
                                    <input type="text" name="email" class="form-control" value="{{ $user->email }}" required>
                                @else
                                    <input type="text" name="email" class="form-control" value="" required>
                                @endif
                            @else
		        			   <input type="text" name="email" class="form-control" value="{{ $user->email }}" readonly>
                            @endif
		        		</div>
                        <div class="form-group clr-blk">
                            <label>Country</label>
                            <select class="form-control" name="country">
                                @foreach($cities as $city)
                                    <option value="{{ $city->code }}" @if($city->code == $user->country) selected='selected' @endif>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group clr-blk">
                            <label for="image">Profile Picture</label>
                            <input id="image" type="file" name="image">
                        </div>
		        		<input type="submit" name="submit" value="Update" class="btn btn-primary">
		        	</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<style type="text/css">


/* only used for background overlay not needed for centering */
form:before {
    content: '';
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    /*background-color: rgba(0,0,0,0.3);*/
    z-index: -1;
    border-radius: 10px;
}
.clr-blk {
	color: #000;
}
</style>
@endsection 