@extends('layouts.app-admin')
@section('content')
<div id="page-content-wrapper">
   <section class="maindiv">
      <div class="container">
         <div class="row headingtop">
            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
               <h2 class="textlog">Profile</h2>
            </div>
         </div>
         <div class="row">
            @if(Session::has('error'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger text-center') }}">
               {{ Session::get('error') }}
            </p>
            @endif
            @if(Session::has('success'))
            <p class="alert {{ Session::get('alert-class', 'alert-success text-center') }}">
               {{ Session::get('success') }}
            </p>
            @endif
         </div>
         <form action="/admin/profile" name="activities_form" method="POST" class="addcontainerpart" enctype="multipart/form-data">
            @csrf
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Name</label>
                     <input type="text" name="name" class="form-control" value="{{$user->name}}" required placeholder="Name">
                  </div>
                  <div class="form-group">
                     <label>Currency Code</label>
                     <select name="currency" class="form-control" required>
                         <option disabled value="">Select Currency</option>
                         <option value="EUR" @if($user->currency == "EUR") selected='selected' @endif label="Euro">EUR</option>
                         <option value="JPY" @if($user->currency == "JPY") selected='selected' @endif label="Japanese yen">JPY</option>
                         <option value="GBP" @if($user->currency == "GBP") selected='selected' @endif label="Pound sterling">GBP</option>
                         <option value="AED" @if($user->currency == "AED") selected='selected' @endif label="United Arab Emirates dirham">AED</option>
                         <option value="AFN" @if($user->currency == "AFN") selected='selected' @endif label="Afghan afghani">AFN</option>
                         <option value="ALL" @if($user->currency == "ALL") selected='selected' @endif label="Albanian lek">ALL</option>
                         <option value="AMD" @if($user->currency == "AMD") selected='selected' @endif label="Armenian dram">AMD</option>
                         <option value="ANG" @if($user->currency == "ANG") selected='selected' @endif label="Netherlands Antillean guilder">ANG</option>
                         <option value="AOA" @if($user->currency == "AOA") selected='selected' @endif label="Angolan kwanza">AOA</option>
                         <option value="ARS" @if($user->currency == "ARS") selected='selected' @endif label="Argentine peso">ARS</option>
                         <option value="AUD" @if($user->currency == "AUD") selected='selected' @endif label="Australian dollar">AUD</option>
                         <option value="AWG" @if($user->currency == "AWG") selected='selected' @endif label="Aruban florin">AWG</option>
                         <option value="AZN" @if($user->currency == "AZN") selected='selected' @endif label="Azerbaijani manat">AZN</option>
                         <option value="BAM" @if($user->currency == "BAM") selected='selected' @endif label="Bosnia and Herzegovina convertible mark">BAM</option>
                         <option value="BBD" @if($user->currency == "BBD") selected='selected' @endif label="Barbadian dollar">BBD</option>
                         <option value="BDT" @if($user->currency == "BDT") selected='selected' @endif label="Bangladeshi taka">BDT</option>
                         <option value="BGN" @if($user->currency == "BGN") selected='selected' @endif label="Bulgarian lev">BGN</option>
                         <option value="BHD" @if($user->currency == "BHD") selected='selected' @endif label="Bahraini dinar">BHD</option>
                         <option value="BIF" @if($user->currency == "BIF") selected='selected' @endif label="Burundian franc">BIF</option>
                         <option value="BMD" @if($user->currency == "BMD") selected='selected' @endif label="Bermudian dollar">BMD</option>
                         <option value="BND" @if($user->currency == "BND") selected='selected' @endif label="Brunei dollar">BND</option>
                         <option value="BOB" @if($user->currency == "BOB") selected='selected' @endif label="Bolivian boliviano">BOB</option>
                         <option value="BRL" @if($user->currency == "BRL") selected='selected' @endif label="Brazilian real">BRL</option>
                         <option value="BSD" @if($user->currency == "BSD") selected='selected' @endif label="Bahamian dollar">BSD</option>
                         <option value="BTN" @if($user->currency == "BTN") selected='selected' @endif label="Bhutanese ngultrum">BTN</option>
                         <option value="BWP" @if($user->currency == "BWP") selected='selected' @endif label="Botswana pula">BWP</option>
                         <option value="BYN" @if($user->currency == "BYN") selected='selected' @endif label="Belarusian ruble">BYN</option>
                         <option value="BZD" @if($user->currency == "BZD") selected='selected' @endif label="Belize dollar">BZD</option>
                         <option value="CAD" @if($user->currency == "CAD") selected='selected' @endif label="Canadian dollar">CAD</option>
                         <option value="CDF" @if($user->currency == "CDF") selected='selected' @endif label="Congolese franc">CDF</option>
                         <option value="CHF" @if($user->currency == "CHF") selected='selected' @endif label="Swiss franc">CHF</option>
                         <option value="CLP" @if($user->currency == "CLP") selected='selected' @endif label="Chilean peso">CLP</option>
                         <option value="CNY" @if($user->currency == "CNY") selected='selected' @endif label="Chinese yuan">CNY</option>
                         <option value="COP" @if($user->currency == "COP") selected='selected' @endif label="Colombian peso">COP</option>
                         <option value="CRC" @if($user->currency == "CRC") selected='selected' @endif label="Costa Rican colón">CRC</option>
                         <option value="CUC" @if($user->currency == "CUC") selected='selected' @endif label="Cuban convertible peso">CUC</option>
                         <option value="CUP" @if($user->currency == "CUP") selected='selected' @endif label="Cuban peso">CUP</option>
                         <option value="CVE" @if($user->currency == "CVE") selected='selected' @endif label="Cape Verdean escudo">CVE</option>
                         <option value="CZK" @if($user->currency == "CZK") selected='selected' @endif label="Czech koruna">CZK</option>
                         <option value="DJF" @if($user->currency == "DJF") selected='selected' @endif label="Djiboutian franc">DJF</option>
                         <option value="DKK" @if($user->currency == "DKK") selected='selected' @endif label="Danish krone">DKK</option>
                         <option value="DOP" @if($user->currency == "DOP") selected='selected' @endif label="Dominican peso">DOP</option>
                         <option value="DZD" @if($user->currency == "DZD") selected='selected' @endif label="Algerian dinar">DZD</option>
                         <option value="EGP" @if($user->currency == "EGP") selected='selected' @endif label="Egyptian pound">EGP</option>
                         <option value="ERN" @if($user->currency == "ERN") selected='selected' @endif label="Eritrean nakfa">ERN</option>
                         <option value="ETB" @if($user->currency == "ETB") selected='selected' @endif label="Ethiopian birr">ETB</option>
                         <option value="EUR" @if($user->currency == "EUR") selected='selected' @endif label="EURO">EUR</option>
                         <option value="FJD" @if($user->currency == "FJD") selected='selected' @endif label="Fijian dollar">FJD</option>
                         <option value="FKP" @if($user->currency == "FKP") selected='selected' @endif label="Falkland Islands pound">FKP</option>
                         <option value="GBP" @if($user->currency == "GBP") selected='selected' @endif label="British pound">GBP</option>
                         <option value="GEL" @if($user->currency == "GEL") selected='selected' @endif label="Georgian lari">GEL</option>
                         <option value="GGP" @if($user->currency == "GGP") selected='selected' @endif label="Guernsey pound">GGP</option>
                         <option value="GHS" @if($user->currency == "GHS") selected='selected' @endif label="Ghanaian cedi">GHS</option>
                         <option value="GIP" @if($user->currency == "GIP") selected='selected' @endif label="Gibraltar pound">GIP</option>
                         <option value="GMD" @if($user->currency == "GMD") selected='selected' @endif label="Gambian dalasi">GMD</option>
                         <option value="GNF" @if($user->currency == "GNF") selected='selected' @endif label="Guinean franc">GNF</option>
                         <option value="GTQ" @if($user->currency == "GTQ") selected='selected' @endif label="Guatemalan quetzal">GTQ</option>
                         <option value="GYD" @if($user->currency == "GYD") selected='selected' @endif label="Guyanese dollar">GYD</option>
                         <option value="HKD" @if($user->currency == "HKD") selected='selected' @endif label="Hong Kong dollar">HKD</option>
                         <option value="HNL" @if($user->currency == "HNL") selected='selected' @endif label="Honduran lempira">HNL</option>
                         <option value="HKD" @if($user->currency == "HKD") selected='selected' @endif label="Hong Kong dollar">HKD</option>
                         <option value="HRK" @if($user->currency == "HRK") selected='selected' @endif label="Croatian kuna">HRK</option>
                         <option value="HTG" @if($user->currency == "HTG") selected='selected' @endif label="Haitian gourde">HTG</option>
                         <option value="HUF" @if($user->currency == "HUF") selected='selected' @endif label="Hungarian forint">HUF</option>
                         <option value="IDR" @if($user->currency == "IDR") selected='selected' @endif label="Indonesian rupiah">IDR</option>
                         <option value="ILS" @if($user->currency == "ILS") selected='selected' @endif label="Israeli new shekel">ILS</option>
                         <option value="IMP" @if($user->currency == "IMP") selected='selected' @endif label="Manx pound">IMP</option>
                         <option value="INR" @if($user->currency == "INR") selected='selected' @endif label="Indian rupee">INR</option>
                         <option value="IQD" @if($user->currency == "IQD") selected='selected' @endif label="Iraqi dinar">IQD</option>
                         <option value="IRR" @if($user->currency == "IRR") selected='selected' @endif label="Iranian rial">IRR</option>
                         <option value="ISK" @if($user->currency == "ISK") selected='selected' @endif label="Icelandic króna">ISK</option>
                         <option value="JEP" @if($user->currency == "JEP") selected='selected' @endif label="Jersey pound">JEP</option>
                         <option value="JMD" @if($user->currency == "JMD") selected='selected' @endif label="Jamaican dollar">JMD</option>
                         <option value="JOD" @if($user->currency == "JOD") selected='selected' @endif label="Jordanian dinar">JOD</option>
                         <option value="JPY" @if($user->currency == "JPY") selected='selected' @endif label="Japanese yen">JPY</option>
                         <option value="KES" @if($user->currency == "KES") selected='selected' @endif label="Kenyan shilling">KES</option>
                         <option value="KGS" @if($user->currency == "KGS") selected='selected' @endif label="Kyrgyzstani som">KGS</option>
                         <option value="KHR" @if($user->currency == "KHR") selected='selected' @endif label="Cambodian riel">KHR</option>
                         <option value="KID" @if($user->currency == "KID") selected='selected' @endif label="Kiribati dollar">KID</option>
                         <option value="KMF" @if($user->currency == "KMF") selected='selected' @endif label="Comorian franc">KMF</option>
                         <option value="KPW" @if($user->currency == "KPW") selected='selected' @endif label="North Korean won">KPW</option>
                         <option value="KRW" @if($user->currency == "KRW") selected='selected' @endif label="South Korean won">KRW</option>
                         <option value="KWD" @if($user->currency == "KWD") selected='selected' @endif label="Kuwaiti dinar">KWD</option>
                         <option value="KYD" @if($user->currency == "KYD") selected='selected' @endif label="Cayman Islands dollar">KYD</option>
                         <option value="KZT" @if($user->currency == "KZT") selected='selected' @endif label="Kazakhstani tenge">KZT</option>
                         <option value="LAK" @if($user->currency == "LAK") selected='selected' @endif label="Lao kip">LAK</option>
                         <option value="LBP" @if($user->currency == "LBP") selected='selected' @endif label="Lebanese pound">LBP</option>
                         <option value="LKR" @if($user->currency == "LKR") selected='selected' @endif label="Sri Lankan rupee">LKR</option>
                         <option value="LRD" @if($user->currency == "LRD") selected='selected' @endif label="Liberian dollar">LRD</option>
                         <option value="LSL" @if($user->currency == "LSL") selected='selected' @endif label="Lesotho loti">LSL</option>
                         <option value="LYD" @if($user->currency == "LYD") selected='selected' @endif label="Libyan dinar">LYD</option>
                         <option value="MAD" @if($user->currency == "MAD") selected='selected' @endif label="Moroccan dirham">MAD</option>
                         <option value="MDL" @if($user->currency == "MDL") selected='selected' @endif label="Moldovan leu">MDL</option>
                         <option value="MGA" @if($user->currency == "MGA") selected='selected' @endif label="Malagasy ariary">MGA</option>
                         <option value="MKD" @if($user->currency == "MKD") selected='selected' @endif label="Macedonian denar">MKD</option>
                         <option value="MMK" @if($user->currency == "MMK") selected='selected' @endif label="Burmese kyat">MMK</option>
                         <option value="MNT" @if($user->currency == "MNT") selected='selected' @endif label="Mongolian tögrög">MNT</option>
                         <option value="MOP" @if($user->currency == "MOP") selected='selected' @endif label="Macanese pataca">MOP</option>
                         <option value="MRU" @if($user->currency == "MRU") selected='selected' @endif label="Mauritanian ouguiya">MRU</option>
                         <option value="MUR" @if($user->currency == "MUR") selected='selected' @endif label="Mauritian rupee">MUR</option>
                         <option value="MVR" @if($user->currency == "MVR") selected='selected' @endif label="Maldivian rufiyaa">MVR</option>
                         <option value="MWK" @if($user->currency == "MWK") selected='selected' @endif label="Malawian kwacha">MWK</option>
                         <option value="MXN" @if($user->currency == "MXN") selected='selected' @endif label="Mexican peso">MXN</option>
                         <option value="MYR" @if($user->currency == "MYR") selected='selected' @endif label="Malaysian ringgit">MYR</option>
                         <option value="MZN" @if($user->currency == "MZN") selected='selected' @endif label="Mozambican metical">MZN</option>
                         <option value="NAD" @if($user->currency == "NAD") selected='selected' @endif label="Namibian dollar">NAD</option>
                         <option value="NGN" @if($user->currency == "NGN") selected='selected' @endif label="Nigerian naira">NGN</option>
                         <option value="NIO" @if($user->currency == "NIO") selected='selected' @endif label="Nicaraguan córdoba">NIO</option>
                         <option value="NOK" @if($user->currency == "NOK") selected='selected' @endif label="Norwegian krone">NOK</option>
                         <option value="NPR" @if($user->currency == "NPR") selected='selected' @endif label="Nepalese rupee">NPR</option>
                         <option value="NZD" @if($user->currency == "NZD") selected='selected' @endif label="New Zealand dollar">NZD</option>
                         <option value="OMR" @if($user->currency == "OMR") selected='selected' @endif label="Omani rial">OMR</option>
                         <option value="PAB" @if($user->currency == "PAB") selected='selected' @endif label="Panamanian balboa">PAB</option>
                         <option value="PEN" @if($user->currency == "PEN") selected='selected' @endif label="Peruvian sol">PEN</option>
                         <option value="PGK" @if($user->currency == "PGK") selected='selected' @endif label="Papua New Guinean kina">PGK</option>
                         <option value="PHP" @if($user->currency == "PHP") selected='selected' @endif label="Philippine peso">PHP</option>
                         <option value="PKR" @if($user->currency == "PKR") selected='selected' @endif label="Pakistani rupee">PKR</option>
                         <option value="PLN" @if($user->currency == "PLN") selected='selected' @endif label="Polish złoty">PLN</option>
                         <option value="PRB" @if($user->currency == "PRB") selected='selected' @endif label="Transnistrian ruble">PRB</option>
                         <option value="PYG" @if($user->currency == "PYG") selected='selected' @endif label="Paraguayan guaraní">PYG</option>
                         <option value="QAR" @if($user->currency == "QAR") selected='selected' @endif label="Qatari riyal">QAR</option>
                         <option value="RON" @if($user->currency == "RON") selected='selected' @endif label="Romanian leu">RON</option>
                         <option value="RSD" @if($user->currency == "RSD") selected='selected' @endif label="Serbian dinar">RSD</option>
                         <option value="RUB" @if($user->currency == "RUB") selected='selected' @endif label="Russian ruble">RUB</option>
                         <option value="RWF" @if($user->currency == "RWF") selected='selected' @endif label="Rwandan franc">RWF</option>
                         <option value="SAR" @if($user->currency == "SAR") selected='selected' @endif label="Saudi riyal">SAR</option>
                         <option value="SEK" @if($user->currency == "SEK") selected='selected' @endif label="Swedish krona">SEK</option>
                         <option value="SGD" @if($user->currency == "SGD") selected='selected' @endif label="Singapore dollar">SGD</option>
                         <option value="SHP" @if($user->currency == "SHP") selected='selected' @endif label="Saint Helena pound">SHP</option>
                         <option value="SLL" @if($user->currency == "SLL") selected='selected' @endif label="Sierra Leonean leone">SLL</option>
                         <option value="SLS" @if($user->currency == "SLS") selected='selected' @endif label="Somaliland shilling">SLS</option>
                         <option value="SOS" @if($user->currency == "SOS") selected='selected' @endif label="Somali shilling">SOS</option>
                         <option value="SRD" @if($user->currency == "SRD") selected='selected' @endif label="Surinamese dollar">SRD</option>
                         <option value="SSP" @if($user->currency == "SSP") selected='selected' @endif label="South Sudanese pound">SSP</option>
                         <option value="STN" @if($user->currency == "STN") selected='selected' @endif label="São Tomé and Príncipe dobra">STN</option>
                         <option value="SYP" @if($user->currency == "SYP") selected='selected' @endif label="Syrian pound">SYP</option>
                         <option value="SZL" @if($user->currency == "SZL") selected='selected' @endif label="Swazi lilangeni">SZL</option>
                         <option value="THB" @if($user->currency == "THB") selected='selected' @endif label="Thai baht">THB</option>
                         <option value="TJS" @if($user->currency == "TJS") selected='selected' @endif label="Tajikistani somoni">TJS</option>
                         <option value="TMT" @if($user->currency == "TMT") selected='selected' @endif label="Turkmenistan manat">TMT</option>
                         <option value="TND" @if($user->currency == "TND") selected='selected' @endif label="Tunisian dinar">TND</option>
                         <option value="TOP" @if($user->currency == "TOP") selected='selected' @endif label="Tongan paʻanga">TOP</option>
                         <option value="TRY" @if($user->currency == "TRY") selected='selected' @endif label="Turkish lira">TRY</option>
                         <option value="TTD" @if($user->currency == "TTD") selected='selected' @endif label="Trinidad and Tobago dollar">TTD</option>
                         <option value="TVD" @if($user->currency == "TVD") selected='selected' @endif label="Tuvaluan dollar">TVD</option>
                         <option value="TWD" @if($user->currency == "TWD") selected='selected' @endif label="New Taiwan dollar">TWD</option>
                         <option value="TZS" @if($user->currency == "TZS") selected='selected' @endif label="Tanzanian shilling">TZS</option>
                         <option value="UAH" @if($user->currency == "UAH") selected='selected' @endif label="Ukrainian hryvnia">UAH</option>
                         <option value="UGX" @if($user->currency == "UGX") selected='selected' @endif label="Ugandan shilling">UGX</option>
                         <option value="USD" @if($user->currency == "USD") selected='selected' @endif label="United States dollar">USD</option>
                         <option value="UYU" @if($user->currency == "UYU") selected='selected' @endif label="Uruguayan peso">UYU</option>
                         <option value="UZS" @if($user->currency == "UZS") selected='selected' @endif label="Uzbekistani soʻm">UZS</option>
                         <option value="VES" @if($user->currency == "VES") selected='selected' @endif label="Venezuelan bolívar soberano">VES</option>
                         <option value="VND" @if($user->currency == "VND") selected='selected' @endif label="Vietnamese đồng">VND</option>
                         <option value="VUV" @if($user->currency == "VUV") selected='selected' @endif label="Vanuatu vatu">VUV</option>
                         <option value="WST" @if($user->currency == "WST") selected='selected' @endif label="Samoan tālā">WST</option>
                         <option value="XAF" @if($user->currency == "XAF") selected='selected' @endif label="Central African CFA franc">XAF</option>
                         <option value="XCD" @if($user->currency == "XCD") selected='selected' @endif label="Eastern Caribbean dollar">XCD</option>
                         <option value="XOF" @if($user->currency == "XOF") selected='selected' @endif label="West African CFA franc">XOF</option>
                         <option value="XPF" @if($user->currency == "XPF") selected='selected' @endif label="CFP franc">XPF</option>
                         <option value="ZAR" @if($user->currency == "ZAR") selected='selected' @endif label="South African rand">ZAR</option>
                         <option value="ZMW" @if($user->currency == "ZMW") selected='selected' @endif label="Zambian kwacha">ZMW</option>
                         <option value="ZWB" @if($user->currency == "ZWB") selected='selected' @endif label="Zimbabwean bonds">ZWB</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label>Address</label>
                     <input type="text" name="address" class="form-control" value="{{$user->address}}" required placeholder="Address">
                  </div>
                  <div class="form-group">
                     <label>Phone</label>
                     <input type="text" name="phone" class="form-control" value="{{$user->phone}}">                     
                  </div>     
                  <div class="form-group">
                    <label>Country</label>
                    <input list="country" id="countryInput" name="country"  value="{{$user->country}}" placeholder="Choose your country" class="form-control" required>
                    <datalist id="country">
                      @foreach($currencies as $currency)
                         <option class="data-list" value="{{$currency->code}}" data-currency="{{$currency->currency_code}}" data-country="{{$currency->code}}">
                      @endforeach
                    </datalist> 
                  </div>                              
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group">
                     <label>Email/Username</label>
                     <input type="text" name="email" class="form-control" value="{{$user->email}}" required placeholder="Email">
                  </div>
                  <div class="form-group">
                     <label>Password <span class="info">(Leave blank if don't want to update)</span></label>
                     <input type="password" name="password" class="form-control" value="" placeholder="Password">
                  </div>
                  <div class="form-group">
                     <label>Confirm Password</label>
                     <input type="password" name="confirm_password" class="form-control" value="" placeholder="Confirm Password">
                  </div>
                  <div class="form-group">
                     <label>Website Logo</label>
                     <input type="file" name="logo" class="form-control" value="">
                     <input type="hidden" name="hidden_logo" class="form-control" value="{{$user->logo}}">                     
                  </div>
                  <div class="form-group">
                     <img src="/uploads/website_logo/{{$user->logo}}" width="150px">
                  </div> 
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <h3>Social Links</h3>
                  <div class="form-group">
                     <label>Facebook Link</label>
                     <input type="url" name="fb_link" class="form-control" value="{{$user->fb_link}}" required placeholder="Facebook Link">
                  </div>
                  <div class="form-group">
                     <label>Instagram Link</label>
                     <input type="url" name="insta_link" class="form-control" value="{{$user->insta_link}}" required placeholder="Instagram Link">
                  </div>
                  <input type="submit" name="submit" class="btn btn-primary" value="Submit">
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                  <div class="form-group"><label></label></div>
                  <div class="form-group">
                     <label>Twitter Link</label>
                     <input type="url" name="twitter_link" class="form-control" value="{{$user->twitter_link}}" required placeholder="Twitter Link">
                  </div>
                  <div class="form-group">
                     <label>Youtube Link</label>
                     <input type="url" name="you_link" class="form-control" value="{{$user->you_link}}" required placeholder="Youtube Link">
                  </div>
               </div>
            </div>
         </form>
      </div>
   </section>
</div>
@endsection