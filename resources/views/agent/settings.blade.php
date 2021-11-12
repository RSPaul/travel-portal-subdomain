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
                                <li class="breadcrumb-item"><a href="/agent/settings">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Settings
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="page-account-settings">
                <div class="row">
                    <!-- left menu section -->
                    <div class="col-md-3 mb-2 mb-md-0">
                        <ul class="nav nav-pills flex-column nav-left">
                            <!-- general -->
                            <li class="nav-item">
                                <a class="nav-link active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
                                    <i data-feather="user" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">General</span>
                                </a>
                            </li>
                            <!-- change password -->
                            <li class="nav-item">
                                <a class="nav-link" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
                                    <i data-feather="lock" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">Change Password</span>
                                </a>
                            </li>
                            <!-- information -->
                            <li class="nav-item">
                                <a class="nav-link" id="account-pill-info" data-toggle="pill" href="#account-vertical-info" aria-expanded="false">
                                    <i data-feather="info" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">Information</span>
                                </a>
                            </li>
                            <!-- social -->
                            <li class="nav-item">
                                <a class="nav-link" id="account-pill-social" data-toggle="pill" href="#account-vertical-social" aria-expanded="false">
                                    <i data-feather="link" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">Social</span>
                                </a>
                            </li>
                            <!-- notification -->
                            <li class="nav-item">
                                <a class="nav-link" id="account-pill-notifications" data-toggle="pill" href="#account-vertical-notifications" aria-expanded="false">
                                    <i data-feather="bell" class="font-medium-3 mr-1"></i>
                                    <span class="font-weight-bold">Notifications</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!--/ left menu section -->

                    <!-- right content section -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- general tab -->
                                    <div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
                                        <!-- header media -->
                                        <div class="media">
                                            <a href="javascript:void(0);" class="mr-25">
                                                <img src="/uploads/profiles/{{ $user->picture }}" id="account-upload-img" class="rounded mr-50 profile-pic-preview" alt="profile image" height="80" width="80" onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=No Image';" />
                                            </a>
                                            <!-- upload and reset button -->
                                            <div class="media-body mt-75 ml-1">
                                                <label for="account-upload" class="btn btn-sm btn-primary mb-75 mr-75">Upload</label>
                                                <input type="file" id="account-upload" hidden accept="image/*" />
                                                <!-- <button class="btn btn-sm btn-outline-secondary mb-75">Reset</button> -->
                                                <p>Allowed JPG, GIF or PNG. Max size of 800kB</p>
                                            </div>
                                            <!--/ upload and reset button -->
                                        </div>
                                        <!--/ header media -->

                                        <!-- form -->
                                        <form class="validate-form mt-2" id="profileGeneral" novalidate action="/api/agent/settings">
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-username">Username</label>
                                                        <input type="text" class="form-control" id="account-username" name="username" placeholder="Username" value="{{ $user->name }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-name">Name</label>
                                                        <input type="text" class="form-control" id="account-name" name="name" placeholder="Name" value="{{ $user->name }}" required/>
                                                        <div class="invalid-feedback">Please enter your name.</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-e-mail">E-mail</label>
                                                        <input type="email" class="form-control" id="account-e-mail" name="email" placeholder="Email" value="{{ $user->email }}" readonly/>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-company">Company Name</label>
                                                        <input type="text" class="form-control" id="account-company" name="company_name" placeholder="Company Name" value="{{ $agent->company_name }}" required/>
                                                        <div class="invalid-feedback">Please enter company name.</div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="website_url">Company Website</label>
                                                        <input type="url" class="form-control" id="website_url" name="website_url" placeholder="Company Website" value="{{ $agent->website_url }}" required/>
                                                        <div class="invalid-feedback">Please enter cmpany website. (https://www.company.com)</div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-company">Years in Bussiness</label>
                                                        <select id="years_in_business" class="form-control" name="years_in_business" required>
                                                            <option value="">Select Years in Bussiness</option>
                                                            <option value="1-2" @if($agent->years_in_business == '1-2') selected="selected" @endif>1-2</option>
                                                            <option value="2-5" @if($agent->years_in_business == '2-5') selected="selected" @endif>2-5</option>
                                                            <option value="5-10" @if($agent->years_in_business == '5-10') selected="selected" @endif>5-10</option>
                                                            <option value="10+" @if($agent->years_in_business == '10+') selected="selected" @endif>10+</option>
                                                        </select>
                                                        <div class="invalid-feedback">Please select years in bussiness.</div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-12 mt-75">
                                                    <div class="alert alert-warning mb-50" role="alert">
                                                        <h4 class="alert-heading">Your email is not confirmed. Please check your inbox.</h4>
                                                        <div class="alert-body">
                                                            <a href="javascript: void(0);" class="alert-link">Resend confirmation</a>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-12">
                                                    <input type="hidden" name="action" value="settings">
                                                    <input type="hidden" name="picture" id="acount-image-src">
                                                    <button type="submit" class="btn btn-primary mt-2 mr-1">Save changes</button>
                                                    <a href="/agent/dashboard" class="btn btn-outline-secondary mt-2">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                        <!--/ form -->
                                    </div>
                                    <!--/ general tab -->

                                    <!-- change password -->
                                    <div class="tab-pane fade" id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
                                        <!-- form -->
                                        <form class="validate-form mt-2 formValidation" id="changePassword" novalidate action="/api/agent/settings" oninput='confirm_password.setCustomValidity(confirm_password.value != password.value ? "Confirm password is not same as new password." : "")'>
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-old-password">Current Password</label>
                                                        <div class="input-group form-password-toggle input-group-merge">
                                                            <input type="password" class="form-control" id="account-old-password" name="current_password" placeholder="Current Password" required/>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text cursor-pointer">
                                                                    <i data-feather="eye"></i>
                                                                </div>
                                                            </div>
                                                            <div class="invalid-feedback">Please enter current password.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-new-password">New Password</label>
                                                        <div class="input-group form-password-toggle input-group-merge">
                                                            <input type="password" id="account-new-password" name="password" class="form-control" placeholder="New Password" required/>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text cursor-pointer">
                                                                    <i data-feather="eye"></i>
                                                                </div>
                                                            </div>
                                                            <div class="invalid-feedback">Please enter new password.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-retype-new-password">Retype New Password</label>
                                                        <div class="input-group form-password-toggle input-group-merge">
                                                            <input type="password" class="form-control" id="account-retype-new-password" name="confirm_password" placeholder="New Password" required/>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text cursor-pointer"><i data-feather="eye"></i></div>
                                                            </div>
                                                            <div class="invalid-feedback">Confirm password is not same as new password.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <input type="hidden" name="action" value="password">
                                                    <button type="submit" class="btn btn-primary mr-1 mt-1">Save changes</button>
                                                    <a href="javascript:void(0);" onclick="return history.back()" class="btn btn-outline-secondary mt-1">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                        <!--/ form -->
                                    </div>
                                    <!--/ change password -->

                                    <!-- information -->
                                    <div class="tab-pane fade" id="account-vertical-info" role="tabpanel" aria-labelledby="account-pill-info" aria-expanded="false">
                                        <!-- form -->
                                        <form class="validate-form" id="profileInformation" novalidate action="/api/agent/settings">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="accountTextarea">Bio</label>
                                                        <textarea name="bio" class="form-control" id="accountTextarea" rows="4" placeholder="Your Bio data here..." required>{{$agent->bio}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-birth-date">Type of Service</label></br>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" name="services[]" type="checkbox"  value="Online Travel Agency" @if(isset($agent['services']) && in_array('Online Travel Agency',$agent['services'])) checked="checked" @endif>
                                                            <label class="form-check-label" for="inlineCheckbox1">Online Travel Agency</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" name="services[]" type="checkbox"  value="Air Travel" @if(isset($agent['services']) && in_array('Air Travel',$agent['services'])) checked="checked" @endif>
                                                            <label class="form-check-label" for="inlineCheckbox1">Air Travel</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" name="services[]" type="checkbox"  value="Accommodations" @if(isset($agent['services']) && in_array('Accommodations',$agent['services'])) checked="checked" @endif>
                                                            <label class="form-check-label" for="inlineCheckbox1">Accommodations</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" name="services[]" type="checkbox"  value="Tour Guide" @if(isset($agent['services']) && in_array('Tour Guide',$agent['services'])) checked="checked" @endif>
                                                            <label class="form-check-label" for="inlineCheckbox1">Tour Guide</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" name="services[]" type="checkbox"  value="Others" @if(isset($agent['services']) && in_array('Others',$agent['services'])) checked="checked" @endif>
                                                            <label class="form-check-label" for="inlineCheckbox1">Others</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="accountSelect">Country</label>
                                                        <input class="form-control" id="accountSelect" value="{{ $user->country }}" name="country" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-website">Any Other Service</label>
                                                        <input type="text" class="form-control" name="other_services" id="account-website" placeholder=Any Other Service" value="{{ $agent->other_services}}" required />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-phone">Monthly Deals</label>
                                                        <select class="form-control"  name="monthly_deals" required>
                                                            <option value="" @if($agent->monthly_deals == '') selected='selected' @endif>Select Monthly Deals</option>
                                                            <option value="1-50" @if($agent->monthly_deals == '1-50') selected='selected' @endif>1-50</option>
                                                            <option value="50-100" @if($agent->monthly_deals == '50-100') selected='selected' @endif>50-100</option>
                                                            <option value="100-300" @if($agent->monthly_deals == '100-300') selected='selected' @endif>100-300</option>
                                                            <option value="300-1000" @if($agent->monthly_deals == '300-1000') selected='selected' @endif>300-1000</option>
                                                            <option value="1000+" @if($agent->monthly_deals == '1000+') selected='selected' @endif>1000+</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-website">Bussiness Phone</label>
                                                        <input class="form-control" name="bussiness_phone" value="{{ $agent->bussiness_phone }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-company">Company Name</label>
                                                        <input type="text" class="form-control" id="account-company" name="company_name" placeholder="Company Name" value="{{ $agent->company_name }}" required/>
                                                        <div class="invalid-feedback">Please enter company name.</div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <input type="hidden" name="action" value="information">
                                                    <button type="submit" class="btn btn-primary mt-1 mr-1">Save changes</button>
                                                    <a href="javascript:void(0);" onclick="return history.back()" class="btn btn-outline-secondary mt-1">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                        <!--/ form -->
                                    </div>
                                    <!--/ information -->

                                    <!-- social -->
                                    <div class="tab-pane fade" id="account-vertical-social" role="tabpanel" aria-labelledby="account-pill-social" aria-expanded="false">
                                        <!-- form -->
                                        <form class="validate-form">
                                            <div class="row">
                                                <!-- social header -->
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i data-feather="link" class="font-medium-3"></i>
                                                        <h4 class="mb-0 ml-75">Social Links</h4>
                                                    </div>
                                                </div>
                                                <!-- twitter link input -->
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-twitter">Twitter</label>
                                                        <input type="text" id="account-twitter" class="form-control" placeholder="Add link" value="https://www.twitter.com" />
                                                    </div>
                                                </div>
                                                <!-- facebook link input -->
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-facebook">Facebook</label>
                                                        <input type="text" id="account-facebook" class="form-control" placeholder="Add link" />
                                                    </div>
                                                </div>
                                                <!-- google plus input -->
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-google">Google+</label>
                                                        <input type="text" id="account-google" class="form-control" placeholder="Add link" />
                                                    </div>
                                                </div>
                                                <!-- linkedin link input -->
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-linkedin">LinkedIn</label>
                                                        <input type="text" id="account-linkedin" class="form-control" placeholder="Add link" value="https://www.linkedin.com" />
                                                    </div>
                                                </div>
                                                <!-- instagram link input -->
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-instagram">Instagram</label>
                                                        <input type="text" id="account-instagram" class="form-control" placeholder="Add link" />
                                                    </div>
                                                </div>
                                                <!-- Quora link input -->
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="account-quora">Quora</label>
                                                        <input type="text" id="account-quora" class="form-control" placeholder="Add link" />
                                                    </div>
                                                </div>

                                                <!-- divider -->
                                                <!-- <div class="col-12">
                                                    <hr class="my-2" />
                                                </div> -->

                                                <!-- <div class="col-12 mt-1">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i data-feather="user" class="font-medium-3"></i>
                                                        <h4 class="mb-0 ml-75">Profile Connections</h4>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-6 col-md-3 text-center mb-1">
                                                            <p class="font-weight-bold">Your Twitter</p>
                                                            <div class="avatar mb-1">
                                                                <span class="avatar-content">
                                                                    <img src="../../../app-assets/images/avatars/11-small.png" alt="avatar img" width="40" height="40" />
                                                                </span>
                                                            </div>
                                                            <p class="mb-0">@johndoe</p>
                                                            <a href="javascript:void(0)">Disconnect</a>
                                                        </div>
                                                        <div class="col-6 col-md-3 text-center mb-1">
                                                            <p class="font-weight-bold mb-2">Your Facebook</p>
                                                            <button class="btn btn-outline-primary">Connect</button>
                                                        </div>
                                                        <div class="col-6 col-md-3 text-center mb-1">
                                                            <p class="font-weight-bold">Your Google</p>
                                                            <div class="avatar mb-1">
                                                                <span class="avatar-content">
                                                                    <img src="../../../app-assets/images/avatars/3-small.png" alt="avatar img" width="40" height="40" />
                                                                </span>
                                                            </div>
                                                            <p class="mb-0">@luraweber</p>
                                                            <a href="javascript:void(0)">Disconnect</a>
                                                        </div>
                                                        <div class="col-6 col-md-3 text-center mb-2">
                                                            <p class="font-weight-bold mb-1">Your GitHub</p>
                                                            <button class="btn btn-outline-primary">Connect</button>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-12">
                                                    <!-- submit and cancel button -->
                                                    <button type="submit" class="btn btn-primary mr-1 mt-1">Save changes</button>
                                                    <a href="javascript:void(0);" onclick="return history.back()" class="btn btn-outline-secondary mt-1">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                        <!--/ form -->
                                    </div>
                                    <!--/ social -->

                                    <!-- notifications -->
                                    <div class="tab-pane fade" id="account-vertical-notifications" role="tabpanel" aria-labelledby="account-pill-notifications" aria-expanded="false">
                                        <div class="row">
                                            <h6 class="section-label mx-1 mb-2">Activity</h6>
                                            <div class="col-12 mb-2">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch1" />
                                                    <label class="custom-control-label" for="accountSwitch1">
                                                        Email me when someone comments onmy article
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch2" />
                                                    <label class="custom-control-label" for="accountSwitch2">
                                                        Email me when someone answers on my form
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="accountSwitch3" />
                                                    <label class="custom-control-label" for="accountSwitch3">Email me hen someone follows me</label>
                                                </div>
                                            </div>
                                            <h6 class="section-label mx-1 mt-2">Application</h6>
                                            <div class="col-12 mt-1 mb-2">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch4" />
                                                    <label class="custom-control-label" for="accountSwitch4">News and announcements</label>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-2">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" checked id="accountSwitch6" />
                                                    <label class="custom-control-label" for="accountSwitch6">Weekly product updates</label>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-75">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="accountSwitch5" />
                                                    <label class="custom-control-label" for="accountSwitch5">Weekly blog digest</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary mt-2 mr-1">Save changes</button>
                                                <a href="javascript:void(0);" onclick="return history.back()" class="btn btn-outline-secondary mt-1">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/ notifications -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ right content section -->
                </div>
            </section>

        </div>
    </div>
</div>
@endsection