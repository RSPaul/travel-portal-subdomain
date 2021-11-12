@extends('layouts.app-header')

@section('content')
<div class="container-fluid"  id="grad1">
    <div class="container">
        <div class="row">
            <div class="col-lg-7" >
                <div style='padding:10px 10px 20px 15px;text-align: left;'>
                    <h1 class="page_title">There's more than 1 million reasons to join us</h1>
                    <video controls width="100%"  playsinline="playsinline" autoplay="autoplay"  loop="loop"  loop autoplay>
                        <source src="{{ asset('promo.mp4') }}" type="video/mp4">
                        <source src="{{ asset('promo.webm') }}" type="video/webm">
                        <source src="{{ asset('promo.ogv') }}" type="video/ogv">
                    </video>
                </div>
            </div>
            <div class="col-lg-5" style='padding:60px 30px;text-align: center;'>
                <div class="card" style='padding:20px 15px;'>
                    <h2><strong>{{ __('Create Free agent account') }}</strong></h2>
                    <!-- <p>Fill all form field to go to next step</p> -->
                    @if(Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
                        {{ Session::get('success') }}
                    </p>
                    @endif
                    @if(Session::has('error'))
                    <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
                        {{ Session::get('error') }}
                    </p>
                    @endif
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form method="POST" id="registerForm" action="" enctype="multipart/form-data" action="" enctype="multipart/form-data">
                                @csrf
                                <!-- progressbar -->
                                <ul id="progressbar">
                                    <li class="active" id="account"><strong>Personal Info</strong></li>
                                    <li id="personal"><strong>Business contact</strong></li>
                                    <li id="payment"><strong>Business Details</strong></li>
                                    <li id="confirm"><strong>Finish</strong></li>
                                </ul> <!-- fieldsets -->
                                <fieldset>
                                    <div class="form-card">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input id="first_name" placeholder="First Name"  data-parsley-trigger="focusout" type="text" class="form-control required  @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name">
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-6">
                                                <input id="last_name" type="text" placeholder="Last Name"  data-parsley-trigger="focusout" class="form-control required @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" >
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12">
                                                <input id="email" type="text" placeholder="Email"  data-parsley-trigger="focusout"  class="form-control required @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" data-parsley-checkemail data-parsley-checkemail-message="Email Address already Exists." data-parsley-trigger="focusout" >
                                                @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <select id="country" class="form-control required @error('country') is-invalid @enderror" name="country"  required autocomplete="country"  data-parsley-trigger="focusout" data-parsley-required-message="Please select your country." data-parsley-required>
                                                    <option value="">Select Country</option>
                                                    <option value="{{$countryCode}}">{{ $countryName }}</option>
                                                    <option value="AF">Afghanistan</option>
                                                    <option value="AX">Åland Islands</option>
                                                    <option value="AE">United Arab Emirates</option>
                                                    <option value="AL">Albania</option>
                                                    <option value="DZ">Algeria</option>
                                                    <option value="AS">American Samoa</option>
                                                    <option value="AD">Andorra</option>
                                                    <option value="AO">Angola</option>
                                                    <option value="AI">Anguilla</option>
                                                    <option value="AQ">Antarctica</option>
                                                    <option value="AG">Antigua and Barbuda</option>
                                                    <option value="AR">Argentina</option>
                                                    <option value="AM">Armenia</option>
                                                    <option value="AW">Aruba</option>
                                                    <option value="AU">Australia</option>
                                                    <option value="AT">Austria</option>
                                                    <option value="AZ">Azerbaijan</option>
                                                    <option value="BS">Bahamas</option>
                                                    <option value="BH">Bahrain</option>
                                                    <option value="BD">Bangladesh</option>
                                                    <option value="BB">Barbados</option>
                                                    <option value="BY">Belarus</option>
                                                    <option value="BE">Belgium</option>
                                                    <option value="BZ">Belize</option>
                                                    <option value="BJ">Benin</option>
                                                    <option value="BM">Bermuda</option>
                                                    <option value="BT">Bhutan</option>
                                                    <option value="BO">Bolivia, Plurinational State of</option>
                                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                    <option value="BA">Bosnia and Herzegovina</option>
                                                    <option value="BW">Botswana</option>
                                                    <option value="BV">Bouvet Island</option>
                                                    <option value="BR">Brazil</option>
                                                    <option value="IO">British Indian Ocean Territory</option>
                                                    <option value="BN">Brunei Darussalam</option>
                                                    <option value="BG">Bulgaria</option>
                                                    <option value="BF">Burkina Faso</option>
                                                    <option value="BI">Burundi</option>
                                                    <option value="KH">Cambodia</option>
                                                    <option value="CM">Cameroon</option>
                                                    <option value="CA">Canada</option>
                                                    <option value="CV">Cape Verde</option>
                                                    <option value="KY">Cayman Islands</option>
                                                    <option value="CF">Central African Republic</option>
                                                    <option value="TD">Chad</option>
                                                    <option value="CL">Chile</option>
                                                    <option value="CN">China</option>
                                                    <option value="CX">Christmas Island</option>
                                                    <option value="CC">Cocos (Keeling) Islands</option>
                                                    <option value="CO">Colombia</option>
                                                    <option value="KM">Comoros</option>
                                                    <option value="CG">Congo</option>
                                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                                    <option value="CK">Cook Islands</option>
                                                    <option value="CR">Costa Rica</option>
                                                    <option value="CI">Côte d'Ivoire</option>
                                                    <option value="HR">Croatia</option>
                                                    <option value="CU">Cuba</option>
                                                    <option value="CW">Curaçao</option>
                                                    <option value="CY">Cyprus</option>
                                                    <option value="CZ">Czech Republic</option>
                                                    <option value="DK">Denmark</option>
                                                    <option value="DJ">Djibouti</option>
                                                    <option value="DM">Dominica</option>
                                                    <option value="DO">Dominican Republic</option>
                                                    <option value="EC">Ecuador</option>
                                                    <option value="EG">Egypt</option>
                                                    <option value="SV">El Salvador</option>
                                                    <option value="GQ">Equatorial Guinea</option>
                                                    <option value="ER">Eritrea</option>
                                                    <option value="EE">Estonia</option>
                                                    <option value="ET">Ethiopia</option>
                                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                                    <option value="FO">Faroe Islands</option>
                                                    <option value="FJ">Fiji</option>
                                                    <option value="FI">Finland</option>
                                                    <option value="FR">France</option>
                                                    <option value="GF">French Guiana</option>
                                                    <option value="PF">French Polynesia</option>
                                                    <option value="TF">French Southern Territories</option>
                                                    <option value="GA">Gabon</option>
                                                    <option value="GM">Gambia</option>
                                                    <option value="GE">Georgia</option>
                                                    <option value="DE">Germany</option>
                                                    <option value="GH">Ghana</option>
                                                    <option value="GI">Gibraltar</option>
                                                    <option value="GR">Greece</option>
                                                    <option value="GL">Greenland</option>
                                                    <option value="GD">Grenada</option>
                                                    <option value="GP">Guadeloupe</option>
                                                    <option value="GU">Guam</option>
                                                    <option value="GT">Guatemala</option>
                                                    <option value="GG">Guernsey</option>
                                                    <option value="GN">Guinea</option>
                                                    <option value="GW">Guinea-Bissau</option>
                                                    <option value="GY">Guyana</option>
                                                    <option value="HT">Haiti</option>
                                                    <option value="HM">Heard Island and McDonald Islands</option>
                                                    <option value="VA">Holy See (Vatican City State)</option>
                                                    <option value="HN">Honduras</option>
                                                    <option value="HK">Hong Kong</option>
                                                    <option value="HU">Hungary</option>
                                                    <option value="IS">Iceland</option>
                                                    <option value="IN">India</option>
                                                    <option value="ID">Indonesia</option>
                                                    <option value="IR">Iran, Islamic Republic of</option>
                                                    <option value="IQ">Iraq</option>
                                                    <option value="IE">Ireland</option>
                                                    <option value="IM">Isle of Man</option>
                                                    <option value="IL">Israel</option>
                                                    <option value="IT">Italy</option>
                                                    <option value="JM">Jamaica</option>
                                                    <option value="JP">Japan</option>
                                                    <option value="JE">Jersey</option>
                                                    <option value="JO">Jordan</option>
                                                    <option value="KZ">Kazakhstan</option>
                                                    <option value="KE">Kenya</option>
                                                    <option value="KI">Kiribati</option>
                                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                                    <option value="KR">Korea, Republic of</option>
                                                    <option value="KW">Kuwait</option>
                                                    <option value="KG">Kyrgyzstan</option>
                                                    <option value="LA">Lao People's Democratic Republic</option>
                                                    <option value="LV">Latvia</option>
                                                    <option value="LB">Lebanon</option>
                                                    <option value="LS">Lesotho</option>
                                                    <option value="LR">Liberia</option>
                                                    <option value="LY">Libya</option>
                                                    <option value="LI">Liechtenstein</option>
                                                    <option value="LT">Lithuania</option>
                                                    <option value="LU">Luxembourg</option>
                                                    <option value="MO">Macao</option>
                                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                    <option value="MG">Madagascar</option>
                                                    <option value="MW">Malawi</option>
                                                    <option value="MY">Malaysia</option>
                                                    <option value="MV">Maldives</option>
                                                    <option value="ML">Mali</option>
                                                    <option value="MT">Malta</option>
                                                    <option value="MH">Marshall Islands</option>
                                                    <option value="MQ">Martinique</option>
                                                    <option value="MR">Mauritania</option>
                                                    <option value="MU">Mauritius</option>
                                                    <option value="YT">Mayotte</option>
                                                    <option value="MX">Mexico</option>
                                                    <option value="FM">Micronesia, Federated States of</option>
                                                    <option value="MD">Moldova, Republic of</option>
                                                    <option value="MC">Monaco</option>
                                                    <option value="MN">Mongolia</option>
                                                    <option value="ME">Montenegro</option>
                                                    <option value="MS">Montserrat</option>
                                                    <option value="MA">Morocco</option>
                                                    <option value="MZ">Mozambique</option>
                                                    <option value="MM">Myanmar</option>
                                                    <option value="NA">Namibia</option>
                                                    <option value="NR">Nauru</option>
                                                    <option value="NP">Nepal</option>
                                                    <option value="NL">Netherlands</option>
                                                    <option value="NC">New Caledonia</option>
                                                    <option value="NZ">New Zealand</option>
                                                    <option value="NI">Nicaragua</option>
                                                    <option value="NE">Niger</option>
                                                    <option value="NG">Nigeria</option>
                                                    <option value="NU">Niue</option>
                                                    <option value="NF">Norfolk Island</option>
                                                    <option value="MP">Northern Mariana Islands</option>
                                                    <option value="NO">Norway</option>
                                                    <option value="OM">Oman</option>
                                                    <option value="PK">Pakistan</option>
                                                    <option value="PW">Palau</option>
                                                    <option value="PS">Palestinian Territory, Occupied</option>
                                                    <option value="PA">Panama</option>
                                                    <option value="PG">Papua New Guinea</option>
                                                    <option value="PY">Paraguay</option>
                                                    <option value="PE">Peru</option>
                                                    <option value="PH">Philippines</option>
                                                    <option value="PN">Pitcairn</option>
                                                    <option value="PL">Poland</option>
                                                    <option value="PT">Portugal</option>
                                                    <option value="PR">Puerto Rico</option>
                                                    <option value="QA">Qatar</option>
                                                    <option value="RE">Réunion</option>
                                                    <option value="RO">Romania</option>
                                                    <option value="RU">Russian Federation</option>
                                                    <option value="RW">Rwanda</option>
                                                    <option value="BL">Saint Barthélemy</option>
                                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                    <option value="KN">Saint Kitts and Nevis</option>
                                                    <option value="LC">Saint Lucia</option>
                                                    <option value="MF">Saint Martin (French part)</option>
                                                    <option value="PM">Saint Pierre and Miquelon</option>
                                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                                    <option value="WS">Samoa</option>
                                                    <option value="SM">San Marino</option>
                                                    <option value="ST">Sao Tome and Principe</option>
                                                    <option value="SA">Saudi Arabia</option>
                                                    <option value="SN">Senegal</option>
                                                    <option value="RS">Serbia</option>
                                                    <option value="SC">Seychelles</option>
                                                    <option value="SL">Sierra Leone</option>
                                                    <option value="SG">Singapore</option>
                                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                                    <option value="SK">Slovakia</option>
                                                    <option value="SI">Slovenia</option>
                                                    <option value="SB">Solomon Islands</option>
                                                    <option value="SO">Somalia</option>
                                                    <option value="ZA">South Africa</option>
                                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                    <option value="SS">South Sudan</option>
                                                    <option value="ES">Spain</option>
                                                    <option value="LK">Sri Lanka</option>
                                                    <option value="SD">Sudan</option>
                                                    <option value="SR">Suriname</option>
                                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                                    <option value="SZ">Swaziland</option>
                                                    <option value="SE">Sweden</option>
                                                    <option value="CH">Switzerland</option>
                                                    <option value="SY">Syrian Arab Republic</option>
                                                    <option value="TW">Taiwan, Province of China</option>
                                                    <option value="TJ">Tajikistan</option>
                                                    <option value="TZ">Tanzania, United Republic of</option>
                                                    <option value="TH">Thailand</option>
                                                    <option value="TL">Timor-Leste</option>
                                                    <option value="TG">Togo</option>
                                                    <option value="TK">Tokelau</option>
                                                    <option value="TO">Tonga</option>
                                                    <option value="TT">Trinidad and Tobago</option>
                                                    <option value="TN">Tunisia</option>
                                                    <option value="TR">Turkey</option>
                                                    <option value="TM">Turkmenistan</option>
                                                    <option value="TC">Turks and Caicos Islands</option>
                                                    <option value="TV">Tuvalu</option>
                                                    <option value="UG">Uganda</option>
                                                    <option value="UA">Ukraine</option>
                                                    <option value="AE">United Arab Emirates</option>
                                                    <option value="GB">United Kingdom</option>
                                                    <option value="US">United States</option>
                                                    <option value="UM">United States Minor Outlying Islands</option>
                                                    <option value="UY">Uruguay</option>
                                                    <option value="UZ">Uzbekistan</option>
                                                    <option value="VU">Vanuatu</option>
                                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                                    <option value="VN">Viet Nam</option>
                                                    <option value="VG">Virgin Islands, British</option>
                                                    <option value="VI">Virgin Islands, U.S.</option>
                                                    <option value="WF">Wallis and Futuna</option>
                                                    <option value="EH">Western Sahara</option>
                                                    <option value="YE">Yemen</option>
                                                    <option value="ZM">Zambia</option>
                                                    <option value="ZW">Zimbabwe</option>
                                                </select>
                                                @error('country')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                        </div> 
                                    </div>
                                    <input type="button" name="next" class="next action-button" value="Next Step" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <div class="row">

                                            <div class="col-md-6">
                                                <input id="company_name" type="text"  data-parsley-trigger="focusout" placeholder="Company Name" class="form-control  @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}"   autocomplete="company_name" >
                                                @error('company_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <input id="website_url" type="text" data-parsley-trigger="focusout" placeholder="http://www.abc.com" data-parsley-type="url" data-parsley-urlstrict class="form-control  @error('website_url') is-invalid @enderror" name="website_url" value="{{ old('website_url') }}"  autocomplete="website_url" autofocus>
                                                @error('website_url')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <input id="bussiness_phone" type="number" data-parsley-trigger="focusout" placeholder="Enter Bussiness Phone" data-parsley-urlstrict class="form-control  @error('bussiness_phone') is-invalid @enderror" name="bussiness_phone" value="{{ old('bussiness_phone') }}"  autocomplete="bussiness_phone" autofocus>
                                                @error('bussiness_phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                        </div>    
                                    </div> 
                                    <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                    <input type="button" name="next" class="next action-button" value="Next Step" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <div class="row">

                                            <div class="col-md-12">


                                                <select id="years_in_business" data-parsley-trigger="focusout" class="form-control   @error('years_in_business') is-invalid @enderror" name="years_in_business" value="{{ old('years_in_business') }}"  autocomplete="years_in_business" autofocus >
                                                    <option value="">Select Years in Bussiness</option>
                                                    <option value="1-2">1-2</option>
                                                    <option value="2-5">2-5</option>
                                                    <option value="5-10">5-10</option>
                                                    <option value="10+">10+</option>
                                                </select>


                                                @error('years_in_business')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <!-- <input id="company_emp" placeholder="How many employees do you have" type="text" class="form-control  @error('company_emp') is-invalid @enderror" name="company_emp" value="{{ old('company_emp') }}"  autocomplete="company_emp" > -->

                                                <select id="company_emp" data-parsley-trigger="focusout" class="form-control   @error('company_emp') is-invalid @enderror" name="company_emp" value="{{ old('company_emp') }}"  autocomplete="company_emp" autofocus >
                                                    <option value="">Select Number of Agents</option>
                                                    <option value="1-5">1-5</option>
                                                    <option value="5-10">5-10</option>
                                                    <option value="10-20">10-20</option>
                                                    <option value="20-50">20-50</option>
                                                    <option value="50+">50+</option>
                                                </select>

                                                @error('company_emp')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">

                                                <label>Type Of Services</label><br />
                                                <input type="checkbox" value="Online Travel Agency" name="services[]" id="service" class="">&nbsp;Online Travel Agency
                                                <input type="checkbox" value="Air Travel" name="services[]" id="service" class="">&nbsp;Air Travel
                                                <input type="checkbox" value="Accommodations" name="services[]" id="service" class="">&nbsp;Accommodations
                                                <input type="checkbox" value="Tour Guide" name="services[]" id="service" class="">&nbsp;Tour Guide
                                                <input type="checkbox" value="Other" name="services[]" id="service" class="">&nbsp;Other

                                                <input id="other_services" placeholder="Any Other Service" type="text" class="form-control  @error('other_services') is-invalid @enderror" name="other_services" value="{{ old('other_services') }}"  autocomplete="other_services" >

                                            </div>

                                            <div class="col-md-12">


                                                <select id="monthly_deals" data-parsley-trigger="focusout" class="form-control   @error('monthly_deals') is-invalid @enderror" name="monthly_deals" value="{{ old('monthly_deals') }}"  autocomplete="monthly_deals" autofocus >
                                                    <option value="">Select Monthly Deals</option>
                                                    <option value="1-50">1-50</option>
                                                    <option value="50-100">50-100</option>
                                                    <option value="100-300">100-300</option>
                                                    <option value="300-1000">300-1000</option>
                                                    <option value="1000+">1000+</option>
                                                </select>


                                                @error('monthly_deals')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">


                                                <textarea placeholder="Please detail your area of expertise (i.e business travel, all inclusives, luxury etc)" id="specific_destinations" class="form-control  @error('specific_destinations') is-invalid @enderror" name="specific_destinations" value="{{ old('specific_destinations') }}" rows="4" cols="100" maxlength="300"></textarea>

                                                @error('specific_destinations')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>


                                        </div>
                                    </div> 
                                    <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                    <input type="button" name="make_payment" class="next action-button" value="Next" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <div class="row">


                                            <div class="col-md-12">
                                                <input id="company_hear" placeholder="Where did you hear about us." type="text" class="form-control @error('company_hear') is-invalid @enderror" name="company_hear" value="{{ old('company_hear') }}" autocomplete="company_hear" >
                                                @error('company_hear')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <input id="password" placeholder="Password MUST be 8 characters, with at least ONE UPPERCASE, LOWERCASE, NUMBER, SPECIAL CHARACTER" type="password" class="form-control required @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" autocomplete="off" required onKeyUp="checkPasswordStrength();">
                                                <span  class="fa fa-eye toggle-password"></span>
                                                <div id="password-strength-status"></div>
                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <input id="confirm_password" placeholder="Confirm Password" type="password" class="form-control required @error('confirm_password') is-invalid @enderror" name="confirm_password" value="{{ old('confirm_password') }}" autocomplete="off" data-parsley-checkpassword data-parsley-checkpassword-message="Password and Confirm Password do not match" data-parsley-trigger="focusout" required>
                                                <span  class="fa fa-eye toggle-cpassword"></span>
                                                @error('confirm_password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <textarea id="comments" placeholder="Enter your comments here." type="text" class="form-control @error('comments') is-invalid @enderror" name="comments" value="{{ old('comments') }}" autocomplete="comments" ></textarea>
                                                @error('comments')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-6">
                                                <label for='id' style="max-height: 43px;" id="id_label" class="btn btn-info" >Photo ID <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="You need to upload National ID document. (Only image files are allowed.)"></i>
                                                    <span></span>
                                                    <input style="visibility: hidden;"  id="id" type="file" class="@error('id') is-invalid @enderror" name="id"  accept="image/*">
                                                </label>
                                                @error('id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label style="max-height: 43px;" id="picture_label" for='picture' class="btn btn-info">Business Card <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Only image files are allowed."></i>
                                                    <span></span>
                                                    <input style="visibility: hidden;" id="picture" type="file" class="@error('picture') is-invalid @enderror" name="picture"   accept="image/*">
                                                </label>
                                            </div>

                                            <div class="col-md-12">
                                                <label style="max-height: 43px;" id="filling_label" for='filing_receipt' class="btn btn-info">Business Filing Receipt <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Only image files are allowed."></i>
                                                    <span></span>
                                                    <input style="visibility: hidden;" id="filing_receipt" type="file" class="@error('filing_receipt') is-invalid @enderror" name="filing_receipt"   accept="image/*">
                                                </label>
                                            </div>

                                            <!-- <div class="col-md-12">
                                                <label for='company_cert'>Company Incorporation Certificate</label>
                                                <input id="company_cert" placeholder="Company Incorporation Certificate" type="file" class="required @error('company_cert') is-invalid @enderror" name="company_cert"   >
                                            </div> -->
                                        </div>
                                    </div>
                                    <input type="hidden" name="state" value="">
                                    <input type="hidden" name="city" value="">
                                    <input type="hidden" name="pin" value="">
                                    <input type="hidden" name="company_yr_rev" value="">
                                    <input type="hidden" name="company_loc" value="">
                                    <input type="hidden" name="company_reg_num" value="">
                                    <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> 
                                    <button type="submit" class="btn btn-primary regsiterAff">
                                        {{ __('Register') }}
                                    </button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container" style="padding:50px;">
    <div class="row" >
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="sub_heading">What is the tripheist.com Affiliate Partner Program</h2>
            </div>
        </div>
        <div class="col-lg-12">
            <p>
                Join the TripHeist Affiliate Partner Program today, and start earning some of the most competitive commissions and bonuses around. Sign up at no cost, and start earning with TripHeist Today! 
                <br/><br/>
                TripHeist is offering you the chance to connect your business to the fastest growing booking platform around the world. 
                <br/><br/>
                As a TripHeist Affiliate Partner, you plug our deals right into your platform. You’ll gain access to some of the most discounted fares and bookings around.
                We’re continually evolving our services and reach. While maintaining a seamless experience for both user and TripHeist Affiliate Partners. 
            </p>
        </div>
    </div>
</div>

<!-- MultiStep Form -->
<div class="container-fluid" id="our_features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="feature_heading">Why should I join the tripheist.com Affiliate Partner Program?</h2>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row" style="background:#fff;padding:80px auto !important;border-radius: 25px;">
            <div class="col-lg-6 desktopOnlyView text-right" style="background:#ccc;border-radius: 25px;padding:0px;margin:0px;">
                <img class="feature_img" src="/images/animate.png" />
            </div>
            <div class="col-lg-6 col-sm-12" style="margin:auto;background:#fff;border-radius: 25px;padding:20px 30px;">
                <div class="packagebox">
                    <div class="ribbon ribbon-top-left">
                        <span>No Monthly Fees</span>
                    </div>
                    <h4 class="packagetitle">Tripheist - Join as agent</h4>
                    <ul class="featurepoints">
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            5% Profit on bookings Hotels
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            5% Profit on bookings Activities
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            5% Profit on bookings Cabs
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            2% Profit on bookings Flights
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            3% Profit on bookings Packages
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            5% Profit on bookings Cruses
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            5% Profit on Cellphone Packages
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            60% Profit on Markups
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            Personal Web analytics
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            Live Video customer support
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            Bonuses on milestones
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            Save a referral link indefinitely
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            Very advanced dashboard
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            Privet Label Website
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success"></i> 
                            <strong>Extreme Liquidity In Payments</strong>
                        </li>
                    </ul>
                    <div style="padding:25px;"> 
                        <a  href="#grad1" style="border-radius:8px;" class="btn btn-primary btn-block">JOIN FREE NOW</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body  text-center">
                        <img class="card-icon-img" src="{{asset('images/icon6.png')}}" alt="Card image cap">
                        <h5 class="card-title">Commissions</h5>
                        <p class="card-text">
                            For each fare,hotel, or activity booked through your portal you will gain some of the most competitive commissions in the market. Our top partners also earn exclusive rewards.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body  text-center">
                        <img class="card-icon-img" src="{{asset('images/icon5.png')}}" alt="Card image cap">
                        <h5 class="card-title">Online Tracking and reporting </h5>
                        <p class="card-text">
                            Our dashboard will allow you to see exactly what’s happening in real time. 
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">   
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body  text-center">
                        <img class="card-icon-img" src="{{asset('images/icon4.png')}}" alt="Card image cap">
                        <h5 class="card-title">We’re happy to help </h5>
                        <p class="card-text">
                            Our dedicated support staff is happy to help you solve any problems you may encounter while using our services.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body  text-center">
                        <img class="card-icon-img" src="{{asset('images/icon3.png')}}" alt="Card image cap">
                        <h5 class="card-title">Over a million properties </h5>
                        <p class="card-text">
                            We have over a million competitively priced hotels and accommodations to choose from
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid" id="how_it_works">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="hiw_heading">How Trip Heist Can Be Your Partner</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card  text-white bg-warning">
                    <h5 class="card-header text-center" >
                        Tailored to your needs.
                    </h5>
                    <div class="card-body  text-center aff-card">
                        <p class="card-text">
                            Our system ensures that all of our services can be integrated seamlessly into your brand and website. 
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card   text-white bg-success">
                    <h5 class="card-header  text-center ">
                        The Choice is Yours
                    </h5>
                    <div class="card-body  text-center aff-card">
                        <p class="card-text">
                            Choose to display the whole world or keep it centered around your region. 
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card   text-white bg-primary">
                    <h5 class="card-header  text-center">
                        Track Your Profits
                    </h5>
                    <div class="card-body  text-center aff-card">
                        <p class="card-text">
                            Our platform allows you to track and follow your earnings. We also offer performance analytics, to help give you that extra edge. 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    "use strict";
    document.addEventListener('DOMContentLoaded', function () {
        // Activate only if not already activated
        if (window.hideYTActivated)
            return;
        // Activate on all players
        let onYouTubeIframeAPIReadyCallbacks = [];
        for (let playerWrap of document.querySelectorAll(".hytPlayerWrap")) {
            let playerFrame = playerWrap.querySelector("iframe");
            let tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            let firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            let onPlayerStateChange = function (event) {
                if (event.data == YT.PlayerState.ENDED) {
                    playerWrap.classList.add("ended");
                } else if (event.data == YT.PlayerState.PAUSED) {
                    playerWrap.classList.add("paused");
                } else if (event.data == YT.PlayerState.PLAYING) {
                    playerWrap.classList.remove("ended");
                    playerWrap.classList.remove("paused");
                }
            };

            let player;
            onYouTubeIframeAPIReadyCallbacks.push(function () {
                player = new YT.Player(playerFrame, {
                    events: {
                        'onStateChange': onPlayerStateChange
                    }
                });
            });

            playerWrap.addEventListener("click", function () {
                let playerState = player.getPlayerState();
                if (playerState == YT.PlayerState.ENDED) {
                    player.seekTo(0);
                } else if (playerState == YT.PlayerState.PAUSED) {
                    player.playVideo();
                }
            });
        }

        window.onYouTubeIframeAPIReady = function () {
            for (let callback of onYouTubeIframeAPIReadyCallbacks) {
                callback();
            }
        };

        window.hideYTActivated = true;
    });
</script>
<style>


    .hytPlayerWrap {
        display: inline-block;
        position: relative;
    }
    .hytPlayerWrap.ended::after {
        content:"";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        cursor: pointer;
        background-color: black;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 64px 64px;
        background-image: url(data:image/svg+xml;utf8;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjgiIGhlaWdodD0iMTI4IiB2aWV3Qm94PSIwIDAgNTEwIDUxMCI+PHBhdGggZD0iTTI1NSAxMDJWMEwxMjcuNSAxMjcuNSAyNTUgMjU1VjE1M2M4NC4xNSAwIDE1MyA2OC44NSAxNTMgMTUzcy02OC44NSAxNTMtMTUzIDE1My0xNTMtNjguODUtMTUzLTE1M0g1MWMwIDExMi4yIDkxLjggMjA0IDIwNCAyMDRzMjA0LTkxLjggMjA0LTIwNC05MS44LTIwNC0yMDQtMjA0eiIgZmlsbD0iI0ZGRiIvPjwvc3ZnPg==);
    }
    .hytPlayerWrap.paused::after {
        content:"";
        position: absolute;
        top: 70px;
        left: 0;
        bottom: 50px;
        right: 0;
        cursor: pointer;
        background-color: black;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 40px 40px;
        background-image: url(data:image/svg+xml;utf8;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249IjEiIHdpZHRoPSIxNzA2LjY2NyIgaGVpZ2h0PSIxNzA2LjY2NyIgdmlld0JveD0iMCAwIDEyODAgMTI4MCI+PHBhdGggZD0iTTE1Ny42MzUgMi45ODRMMTI2MC45NzkgNjQwIDE1Ny42MzUgMTI3Ny4wMTZ6IiBmaWxsPSIjZmZmIi8+PC9zdmc+);
    }

    .feature_img{
        max-width:400px;
    }

    .packagetitle{
        padding:15px;
        background:#FF7235;
        color:#fff;
        text-align:center;
        text-transform: uppercase;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        text-align: right;
        font-size:15px;
        font-weight:bold;
    }
    .packagebox{
        position: relative;
        max-width: 350px;
        background: #fff;
        border:2px solid #FF7235;
        box-shadow: 0 0 15px rgba(0,0,0,.1);
        border-radius: 8px;
    }

    .packagebox ul{
        padding:50px 25px 5px 45px;   
    }

    .featurepoints li{
        padding:5px;
        font-size:15px;
    }

    /* common */
    .ribbon {
        width: 150px;
        height: 150px;
        overflow: hidden;
        position: absolute;
    }
    .ribbon::before,
    .ribbon::after {
        position: absolute;
        z-index: -1;
        content: '';
        display: block;
        border: 5px solid #2980b9;
    }
    .ribbon span {
        position: absolute;
        display: block;
        width: 225px;
        padding: 15px 0;
        background-color: #1e4355;
        box-shadow: 0 5px 10px rgba(0,0,0,.1);
        color: #fff;
        font: 700 18px/1 'Lato', sans-serif;
        text-shadow: 0 1px 1px rgba(0,0,0,.2);
        text-transform: uppercase;
        text-align: center;
        font-size:12px;
    }

    /* top left*/
    .ribbon-top-left {
        top: -7px;
        left: -7px;
    }
    .ribbon-top-left::before,
    .ribbon-top-left::after {
        border-top-color: transparent;
        border-left-color: transparent;
    }
    .ribbon-top-left::before {
        top: 0;
        right: 0;
    }
    .ribbon-top-left::after {
        bottom: 0;
        left: 0;
    }
    .ribbon-top-left span {
        right: -25px;
        top: 30px;
        transform: rotate(-45deg);
    }

    input[type=file]{
        width:100% !important;
        border: 1px solid #ccc;
        background: #ccc;
    }

    input[type=file]:before{
        border-radius:0px;
        content: none !important;
        text-align: left;
    }
    #grad1 {
        background-color: #9C27B0;
        background-image: linear-gradient(120deg, #FF4081, #81D4FA)
    }

    #registerForm {
        text-align: center;
        position: relative;
        margin-top: 20px
    }

    #registerForm fieldset .form-card {
        background: white;
        border: 0 none;
        border-radius: 0px;
        padding: 20px 10px 0px 10px;
        box-sizing: border-box;
        width: 94%;
        margin: 0 3% 20px 3%;
        position: relative
    }

    #registerForm fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        margin: 0;
        padding-bottom: 0px;
        position: relative
    }

    #registerForm fieldset:not(:first-of-type) {
        display: none
    }

    #registerForm fieldset .form-card {
        text-align: left;
        color: #9E9E9E
    }

    #registerForm input,
    #registerForm textarea,
    #registerForm select {
        padding: 0px 8px 4px 8px;
        border-radius: 2px;
        margin-bottom: 20px;
        margin-top: 2px;
        box-sizing: border-box;
        color: #2C3E50;
        font-size: 14px;
        letter-spacing: 1px
    }

    #registerForm input:focus,
    #registerForm textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        font-weight: bold;
        outline-width: 0
    }

    #registerForm .action-button {
        max-width: 200px;
        background: skyblue;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 25px;
        cursor: pointer;
        padding: 10px 15px;
        margin: 10px 5px
    }

    #registerForm .action-button:hover,
    #registerForm .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
    }

    #registerForm .action-button-previous {
        max-width: 200px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 25px;
        cursor: pointer;
        padding: 10px 15px;
        margin: 10px 5px
    }

    #registerForm .action-button-previous:hover,
    #registerForm .action-button-previous:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
    }

    select.list-dt {
        border: none;
        outline: 0;
        border-bottom: 1px solid #ccc;
        padding: 2px 5px 3px 5px;
        margin: 2px
    }

    select.list-dt:focus {
        border-bottom: 2px solid skyblue
    }

    .card {
        z-index: 0;
        border: none;
        border-radius: 0.5rem;
        position: relative
    }

    .fs-title {
        font-size: 25px;
        color: #2C3E50;
        margin-bottom: 10px;
        font-weight: bold;
        text-align: left
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #000000
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        width: 25%;
        float: left;
        position: relative
    }

    #progressbar #account:before {
        font-family: FontAwesome;
        content: "\f023"
    }

    #progressbar #personal:before {
        font-family: FontAwesome;
        content: "\f007"
    }

    #progressbar #payment:before {
        font-family: FontAwesome;
        content: "\f09d"
    }

    #progressbar #confirm:before {
        font-family: FontAwesome;
        content: "\f00c"
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #6DB5CA;
    }

    .radio-group {
        position: relative;
        margin-bottom: 25px
    }

    .radio {
        display: inline-block;
        width: 204;
        height: 104;
        border-radius: 0;
        background: lightblue;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
        cursor: pointer;
        margin: 8px 2px
    }

    .radio:hover {
        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
    }

    .radio.selected {
        box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
    }

    .fit-image {
        width: 100%;
        object-fit: cover
    }
    #registerForm .input-error {
        border-color: #d03e3e !important;
        color: #d03e3e !important;
    }
    .page_title{
        color:#fff;
        font-size:35px;
        text-align: left;
        padding-bottom:25px;
        font-weight: bold;
        padding-top:25px;
    }
    .sub_heading{
        font-size: 25px;
        font-weight: bold;
        text-align: center;
        padding-bottom: 25px;
    }
    .card-icon-img{
        width:80px;
        padding:15px;
    }
    #our_features{
        background:#6DB5CA;
        padding:50px 25px 80px 25px; 
    }
    #our_features .card{
        min-height: 300px;
        margin-top:25px;
    }
    #how_it_works .card{
        margin-top:25px;
    }
    .feature_heading{
        font-size: 25px;
        font-weight: bold;
        text-align: center;
        padding-bottom: 40px;
        color:#fff;
    }
    #how_it_works{
        padding:50px 25px 80px 25px; 
    }
    .hiw_heading{
        font-size: 25px;
        font-weight: bold;
        text-align: center;
        padding-bottom: 40px;
    }
    #how_it_works .card-title{
        font-weight: bold;
        font-size: 25px;
        color:#fff;
    }
    #how_it_works .card-text{
        font-size: 15px;
    }
    #how_it_works .card-body{
        background: #fff;
        border:1px solid #ccc;
    }
    #how_it_works .card-header{
        background: #6DB5CA;
    }
    #password-strength-status {
        padding: 5px 5px;
        color: #ff0000;
        border-radius: 4px;
        margin-top: 0;
        font-size: 12px;
    }

    #password-strength-status.medium-password {
        color: #ff0000;
    }

    #password-strength-status.weak-password {
        color: #ff0000;
    }

    #password-strength-status.strong-password {
        color: #468847;
    }
    input#password {
        margin-bottom: 5px;
    }

    @media (max-width: 767px) {
        .packagebox ul{
            padding:50px 5px 5px 10px;   
        }
        .feature_img{
            max-width:280px;
        }
    }

</style>
<script>
    $(document).ready(function () {

        $('[data-toggle="tooltip"]').tooltip();

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;


        $(".next").click(function () {

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            var next_step = true;

            current_fs.find('input[type="text"],input[type="email"],input[type="password"],select[id="country"]').each(function () {
                if ($(this).hasClass('required') && $(this).val() == "") {
                    $(this).addClass('input-error');
                    next_step = false;
                } else {

                    $(this).removeClass('input-error');
                }
            });

            if (next_step) {
                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function (now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({'opacity': opacity});
                    },
                    duration: 600
                });
            }
        });

        $(".previous").click(function () {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

//Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
            previous_fs.show();

//hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function (now) {
// for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({'opacity': opacity});
                },
                duration: 600
            });
        });

        $('.radio-group .radio').click(function () {
            $(this).parent().find('.radio').removeClass('selected');
            $(this).addClass('selected');
        });

        $(".submit").click(function () {
            return false;
        })

        $("#picture").change(function () {
            var filename = this.files[0].name;
            var tempPictureName = filename;
            if (filename.length > 10) {
                tempPictureName = filename.substring(0, 10);
                tempPictureName = tempPictureName + '...';
            }
            $("#picture_label span").html(tempPictureName);
            $("#picture_label").css('max-height', '53px');
        });

        $("#id").change(function () {
            var filename = this.files[0].name;
            var tempName = filename;
            if (filename.length > 10) {
                tempName = filename.substring(0, 10);
                tempName = tempName + '...';
            }
            $("#id_label span").html(tempName);
            $("#id_label").css('max-height', '53px');
        });

        $("#filing_receipt").change(function () {
            var filename = this.files[0].name;
            var tempPictureName = filename;
            if (filename.length > 10) {
                tempPictureName = filename.substring(0, 10);
                tempPictureName = tempPictureName + '...';
            }
            $("#filling_label span").html(tempPictureName);
            $("#filling_label").css('max-height', '53px');
        });

    });

    function checkPasswordStrength() {
        var number = /([0-9])/;
        var alphabets = /([a-zA-Z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
        if ($('#password').val().length < 8) {
            $('#password-strength-status').removeClass();
            $('#password-strength-status').addClass('weak-password');
            $('#password-strength-status').html("Weak (should be atleast 8 characters.)");
            $('.regsiterAff').prop("disabled", true);
        } else {
            if ($('#password').val().match(number) && $('#password').val().match(alphabets) && $('#password').val().match(special_characters)) {
                $('#password-strength-status').removeClass();
                $('#password-strength-status').addClass('strong-password');
                $('#password-strength-status').html("Strong Password");
                if ($('#password').val() === $('#confirm_password').val()) {
                    $('.regsiterAff').removeAttr("disabled");
                }
            } else {
                $('#password-strength-status').removeClass();
                $('#password-strength-status').addClass('medium-password');
                $('#password-strength-status').html("Medium (should include alphabets, numbers and special characters.)");
                $('.regsiterAff').prop("disabled", true);
            }
        }
    }

</script>
@endsection
