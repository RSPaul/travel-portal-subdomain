@extends('layouts.app-header')

@section('content')
<section>
	<div class="container">
		<br>
		<div class="row justify-content-center">
			<form action="" method="POST">
				@if($password_changed == 0) 
					<h4>You need to change your password to proceed.</h4>
				@else
					<h4>Enter Current Password To Change Password</h4>
				@endif
				<br>
				@if ($message)
				        <div class="alert alert-danger" role="alert" >
				            {{ $message }}
				        </div>
				    @endif
			  <div class="form-group">
			    <label for="email">Current Password</label>
			    <input type="password" class="form-control" id="current_password" name="current_password" required>
			  </div>
			  <div class="form-group">
			    <label for="pwd">New Password</label>
			    <input type="password" class="form-control" id="password" name="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
			  </div>
			  <div class="form-group">
			    <label for="pwd">Confirm Password</label>
			    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
			  </div>
			  @csrf
			  <button type="submit" class="btn btn-primary">Change Password</button>
			<br><br><br>
			</form>
		</div>
	</div>
</section>
@endsection 