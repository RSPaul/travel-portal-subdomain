<div>
                  
        
    
    
    <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
        <tbody>
            <tr>
                <td style="padding-top:10px">
                    <table width="630" cellpadding="15" bgcolor="#ffffff" cellspacing="0" border="0" align="center" style="padding-top:2em;border-top-left-radius:7px;border-top-right-radius:7px;border-bottom:1px solid #cccccc;margin:0 auto">
                        <tbody>
                            <tr>
                                <td width="100%">
                                    
                                    <table width="100%" align="left" border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            
                                            <tr>
                                                <td align="left" bgcolor="#ffffff" width="40%">
                                                    
                                                        <a href="" rel="noreferrer" target="_blank" >
                                                            <img width="120" border="0" alt="" style="display:block;border:none;outline:none;text-decoration:none" src="https://tripheist.com/images/logo.png" class="CToWUd">
                                                        </a>
                                                    
                                                </td>
                                                 <td width="45%" valign="middle" align="right">
                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:15px;color:#9b9b9b;margin:0;margin-bottom:5px">Booking ID: <span style="color:#000000;font-weight:bold;font-size:18px">{{ $booking->booking_id }}</span></p>
                                                     

                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:12px;color:#9b9b9b;margin:0">Booked on: {{ $booking->created_at }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    

        

    <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
        <tbody>
            <tr>
                <td>
                    <table width="630" cellpadding="0" cellspacing="0" border="0" align="center" style="background:#ffffff;margin:0 auto">
                        <tbody>
                            <tr>
                                <td width="100%" style="padding:20px">
                                    <p></p><table width="100%" align="left" cellpadding="0" cellspacing="0" border="0">
                                        <tbody>








                                            <tr>
                                                <td>
                                                    <table width="100%" cellpadding="15" bgcolor="#005dbd" cellspacing="0" border="0" align="center" style="padding-top:2em;border-bottom-left-radius:7px;border-bottom-right-radius:7px;border-top-left-radius:7px;border-top-right-radius:7px;border-bottom:1px #cccccc;margin:0 auto">
                                                        <tbody>
                                                            <tr>
                                                                 <td width="70%">
                                                                    
                                                                        <h1 style="font-family:arial,sans-serif;font-weight:normal;font-size:30px;color:#fffff0;text-align:left;line-height:1.0">Your Booking is Confirmed</h1>
                                                                        <p style="font-family:Helvetica,arial,sans-serif;font-size:12px;color:#fffff0;margin:0;padding-bottom:5px"> *Protected by our 100% Money-back Guarantee. <a href="{{ env('APP_URL') }}?show=hotels">Learn More </a>  </p>
                                                                 </td>
                                                                    <td width="30%">
                                                                        <div align="right">
                                                                            <!-- <img style="width:76%;line-height:1.0" alt="banner" src="https://ci6.googleusercontent.com/proxy/bzY2IWCd21Z08SuqEgKKy8c28R0NDO12tw1TPQSjJJY0NMPwQk73Rd021FAtR-P5wZ_x-R1Um_LupqT0Nu7iQFD8riIw=s0-d-e1-ft#https://gos3.ibcdn.com/gostays_icon-1569239588.png" class="CToWUd">
                                                                            <span style="font-family:Helvetica,arial,sans-serif;color:#fffff0;font-size:13px;margin:0;padding-bottom:5px;padding-top:20px">  Goibibo Verified Hotel</span> -->
                                                                        </div>
                                                                    </td>
                                                            </tr>
                                                    
                                                            <tr><td>
                                                            <p style="font-family:Helvetica,arial,sans-serif;font-size:12px;color:#fffff0;margin:0;padding-bottom:5px;text-align:center"><b>Checkin</b>
                                                               {{ $booking->request_data['checkInDate'] }} <b>Checkout</b>
                                                                {{ $booking->request_data['checkOutDate'] }}  </p>
                                                                </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>

                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#4a4a4a;font-weight:bold;line-height:25px;text-align:left;margin-top:15px;margin-bottom:0;display:inline-block;padding:0">Dear {{ $booking->request_data['bookingData']['HotelPassenger'][1][0]['FirstName'] }}&nbsp;{{ $booking->request_data['bookingData']['HotelPassenger'][1][0]['LastName'] }},</p>

                                            


                                                   <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                        @if($booking->request_data['isVoucherBooking'] == false)
                                                            Your booking is reserved, please click generate voucher button under my bookings before {{ $booking->request_data['lastCancellationDate'] }}, otherwise your booking will be cancelled.
                                                        @else
                                                           Congrats! Your booking is confirmed for <b>{{ $booking->request_data['hotelName'] }}.</b>
                                                       @endif

                                                        

                                                        
                                                            </p><p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                            ??? You can check-In 
                                                            any time after <b>{{ $booking->request_data['checkInDate'] }}.</b> 
                                                           We request you to inform your hotel in case you are planning to check-In early/late.

                                                            </p>
                                                    <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                            ??? We have received your payment for <b> {{ sizeof($booking->request_data['bookingData']['HotelPassenger']) }} guest in {{ $booking->request_data['noofrooms'] }} room for {{ $booking->request_data['noOfNights'] }} nights.</b>
                                                    </p>

                                                        </td></tr><tr>
                                                            <td style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">









                                                                    </td></tr><tr>
                                                                        <td style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                                            
                                                                            <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                                                
                                                                            </p>

                                                                            
                                                                                <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                                                ??? Don't forget to carry your Local ID.
                                                                                </p>
                                                                            
                                                                        </td>
                                                                    </tr>

                                        

                                            
                                            
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    
    
    <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
        <tbody>
            <tr>
                <td width="100%">
                    <table bgcolor="#ffffff" width="630" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                        <tbody>
                            
                            <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="padding:0px 0">
                                                
                                                <table width="270" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 20px 5px 0">
                                                                   
                                                                    <a href="{{ env('APP_URL') }}?show=hotels" rel="noreferrer" target="_blank" >
                                                                        @if(strpos($hotel_data['hotel_image'], 'http') !== false || strpos($hotel_data['hotel_image'], 'www') !== false)
                                                                            <img style="height:270px" src="{{ $hotel_data['hotel_image'] }}" width="260" alt="Supporting image 1" class="CToWUd">
                                                                        @else
                                                                            <img style="height:270px" src="{{env('AWS_BUCKET_URL')}}/{{ $hotel_data['hotel_image'] }}" width="260" alt="Supporting image 1" class="CToWUd">
                                                                        @endif
                                                                    </a>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                
                                                <table width="290" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px">

                                                                    <p style="color:#3b7dc0;font-family:Arial,Helvetica,sans-serif;font-size:18px;line-height:20px;margin-top:0;margin-bottom:5px;font-weight:normal"><a href="{{ env('APP_URL') }}?show=hotels" rel="noreferrer" target="_blank" >{{ $booking->request_data['hotelName'] }}</a>
                                                                    </p>
                                                                    <p style="font-family:Arial,Helvetica,sans-serif;font-size:14px;margin-top:7px;margin-bottom:0;margin-left:0;margin-right:0;line-height:20px">
                                                                        {{ $hotel_data['hotel_address'] }}
                                                                    </p>
                                                                    
                                                                        <p style="font-family:Arial,Helvetica,sans-serif;font-size:14px;margin-top:7px;margin-bottom:0;margin-left:0;margin-right:0">Phone: <a href="tel:{{ $hotel_data['hotel_phone'] }}" rel="noreferrer" target="_blank">{{ $hotel_data['hotel_phone'] }}</a></p>
                                                                    
                                                                    
                                                                        <p style="font-family:Arial,Helvetica,sans-serif;font-size:14px;margin-top:7px;margin-bottom:0;margin-left:0;margin-right:0">Fax: <a href="tel:{{ $hotel_data['hotel_fax'] }}" rel="noreferrer" target="_blank">{{ $hotel_data['hotel_fax'] }}</a></p>
                                                                    
                                                                    <p style="font-family:Arial,Helvetica,sans-serif;font-size:14px;margin-top:7px;margin-bottom:0;margin-left:0;margin-right:0">Getting there: <a href="https://maps.google.com?daddr={{ $hotel_data['hotel_location'] }}" rel="noreferrer" target="_blank" >Show directions</a></p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px 30px" width="100%">
                                    
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:10px 0 15px 0">
                        
                                                    <table width="100%" cellpadding="0" cellspacing="0" align="center">
                                                        <tbody>
                                                            <tr>
                                                                 
                                                            </tr><tr>
                                                            <td style="font-family:arial,sans-serif;font-weight:normal;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center">
                                                                <p style="Margin-bottom:10px;Margin-top:0">
                                                                    <a href="{{ env('APP_URL') }}/user/bookings" style="color:#ffffff;background-color:#f0741a;font-size:18px;border-radius:4px;padding-top:10px;padding-bottom:10px;padding-right:15px;padding-left:15px;text-decoration:none;width:222px;display:inline-block;Margin-top:5px" rel="noreferrer" target="_blank" >Manage Booking</a>
                                                                </p>
                                                            </td>
                                                            
                                                                <td style="font-family:arial,sans-serif;font-weight:normal;padding-top:10px;padding-bottom:10px;padding-right:10px;padding-left:10px;text-align:center">
                                                                    <p style="Margin-bottom:10px;Margin-top:0">
                                                                        <a href="{{ env('APP_URL') }}/user/e-ticket/hotel/{{ $booking->booking_id }}" style="color:#ffffff;background-color:#f0741a;font-size:18px;border-radius:4px;padding-top:10px;padding-bottom:10px;padding-right:15px;padding-left:15px;text-decoration:none;width:222px;display:inline-block;Margin-top:5px" rel="noreferrer" target="_blank" >Download E-ticket</a>
                                                                    </p>
                                                                </td>
                                                            
                                                        </tr>
                                                            
                                                            
                                                        </tbody>
                                                    </table>
                        
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </td>
                            </tr>

            
            

            
                            <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            @php $counter = 1; @endphp
                                            <tr>
                                                <td width="100%" style="border-bottom:1px solid #cccccc;padding-bottom:20px">
                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:14px;color:#858585;margin:0;padding-bottom:5px">
                                                        {{ $booking->request_data['bookingData'][0]['RoomTypeName'] }}
                                                    </p>
                                                </td>
                                            </tr>
                                            @foreach($booking->request_data['bookingData'] as $key => $room)
                                            @if($key !== 'HotelPassenger')
                                            <tr>
                                                <td width="100%" style="border-bottom:1px solid #cccccc;padding-bottom:20px">
                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:14px;color:#858585;margin:0;padding-bottom:5px">Room {{ $counter }}</p>
                                                    <span style="font-family:Helvetica,arial,sans-serif;font-size:18px;display:inline-block"><img style="float:left;padding-right:10px" src="https://ci6.googleusercontent.com/proxy/SPKseYfmznJaSf9N2V5KQKG6I_DESRiWIdSV7U_QDBTTNFybMIJ2p7ZvzCtgxgl6EsaJoTW0_0WAXyANLEk7v10fvxMYX6REkQ=s0-d-e1-ft#http://gos3.ibcdn.com/hjuls8bqkp4op248fja84esc003i.png" class="CToWUd">
                                                       
                                                       {{ sizeof($booking->request_data['bookingData']['HotelPassenger'][$counter]) }} Guests
                                                        
                                                    </span>

                                                </td>
                                            </tr>
                                            @php $counter++; @endphp
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                
                                                <table width="270" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="270" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="45%">
                                                                                <p style="font-family:Helvetica,arial,sans-serif;font-size:18px;color:#000000;margin:0;padding-bottom:4px">Check In</p>
                                                                                
                                                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#000000;margin:0;padding-top:2px">{{ $booking->request_data['checkInDate'] }}</p>
                                                                                
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                

                                                <table width="270" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="270" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <p style="font-family:Helvetica,arial,sans-serif;font-size:18px;color:#000000;margin:0;padding-bottom:4px">Check Out</p>
                                                                                <p style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#000000;margin:0;padding-top:2px">{{ $booking->request_data['checkOutDate'] }}</p>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                
                                                <table width="270" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="45%">
                                                                                <p style="font-family:Helvetica,arial,sans-serif;font-size:14px;color:#858585;margin:0;width:100%;float:left;padding-bottom:5px">Booking Confirmation</p>
                                                                                <span style="font-family:Helvetica,arial,sans-serif;font-size:18px;display:inline-block">{{ $booking->confirmation_number }}</span>
                                                                            </td>
                                                                        </tr>

                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                
                                                
                                                <table width="270" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                 
                                                                                 <p style="font-family:Helvetica,arial,sans-serif;font-size:14px;color:#858585;margin:0;padding-bottom:5px">Total Amount</p>
                                                                                <span style="font-family:Helvetica,arial,sans-serif;font-size:18px;display:inline-block">{{ $booking->request_data['bookingData'][0]['Price']['CurrencyCode'] }} {{ number_format($booking->request_data['amount'],2) }}</span>
                                                                                 
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>

                            @if($booking->request_data['bookingData'][0]['Price']['CurrencyCode'] == 'ILS')
                                <tr>
                                    <td style="padding:0 30px">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td width="100%" style="border-bottom:1px solid #cccccc;padding:20px 0">
                                                        <p style="font-family:Helvetica,arial,sans-serif;font-size:18px;color:#000000;margin:0;padding-top:2px">.???????????? ?????????? ???????????? ?????????? 17% ????"?? ???????????? ???????????? ??????????, ???????? ?????????? ???????? *</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                            
                            <tr>
                                <td style="padding:0 30px">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="100%" style="border-bottom:1px solid #cccccc;padding:20px 0">
                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:18px;color:#000000;margin:0;padding-top:2px">Room Type: {{ $booking->request_data['bookingData'][0]['RoomTypeName'] }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#faf9f9" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                
                                                <table width="270" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="45%">
                                                                                <p style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#2bac36;margin:0;width:100%;float:left;padding-bottom:5px">Promo Code Applied</p>
                                                                                <span style="font-family:Helvetica,arial,sans-serif;font-size:18px;color:#767505;display:inline-block;font-weight:bold">GETSETGO</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                
                                                <table width="270" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <p style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#2bac36;margin:0;padding-bottom:5px">Total Savings</p>
                                                                                <span style="font-family:Helvetica,arial,sans-serif;color:#767505;font-size:18px;display:inline-block;font-weight:bold">Rs. 1050</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr> -->
                            
                            
                            
                            
                            
                           
                                
                            

                           
                            
                            
                            
                            <tr>
                                <td width="100%" style="padding:20px 30px">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="100%" style="border-bottom:1px solid #cccccc;padding:0">
                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:20px;color:#5c5c5c;margin:0;padding-bottom:10px"><a href="https://maps.google.com?daddr={{ $hotel_data['hotel_location'] }}">Hotel Location Map</a></p>
                                                    <a href="https://maps.google.com?daddr={{ $hotel_data['hotel_location'] }}" rel="noreferrer" target="_blank" >
                                                    <img src="https://tripheist.com/images/mapBgSmall.png" style="width:100%;float:left" class="CToWUd"></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            
                            
                            <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="100%" style="padding-bottom:10px">
                                                    <span style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:18px;color:#4a4a4a;display:inline-block">Room Details</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @php $counter = 1; @endphp
                            @foreach($booking->request_data['bookingData'] as $key => $room)
                              @if($key !== 'HotelPassenger')
                              <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                
                                                <table width="170" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="170" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="30%">
                                                                               <span style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:15px;display:inline-block;color:#4a4a4a;padding-right:10px">Number of Guests</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                
                                                <table width="370" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="370" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                 <span style="font-family:Helvetica,arial,sans-serif;font-size:15px;display:inline-block;color:#4a4a4a;padding-top:2px">Room: {{ $counter }}, {{ sizeof($booking->request_data['bookingData']['HotelPassenger'][$counter]) }} Guest  </span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                              </tr>
                              
                              
                              <tr>
                                  <td style="padding:0 30px" width="100%">
                                      <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                          <tbody><tr>
                                              <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                  
                                                  <table width="170" align="left" border="0" cellpadding="0" cellspacing="0">
                                                      <tbody>
                                                          <tr>
                                                              <td width="100%" align="left" style="padding:5px 0">
                                                                  <table width="100%" cellspacing="0" cellpadding="0">
                                                                      <tbody>
                                                                          <tr>
                                                                              <td width="30%">
                                                                                 <span style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:15px;display:inline-block;color:#4a4a4a">Inclusions </span>
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </td>
                                                          </tr>
                                                       </tbody>
                                                  </table>
                                                  
                                                  
                                                  <table width="370" align="left" border="0" cellpadding="0" cellspacing="0">
                                                      <tbody>
                                                          <tr>
                                                              <td width="100%" align="left" style="padding:5px 0">
                                                                  <table width="100%" cellspacing="0" cellpadding="0">
                                                                      <tbody>
                                                                          <tr>
                                                                              <td>
                                                                                  @foreach($room['Inclusion'] as $inc)
                                                                                   <span style="font-family:Helvetica,arial,sans-serif;font-size:15px;display:inline-block;color:#4a4a4a;padding-top:2px"> {{ $inc }}&nbsp;</span>
                                                                                   @endforeach
                                                                              </td>
                                                                          </tr>
                                                                      </tbody>
                                                                  </table>
                                                              </td>
                                                          </tr>
                                                      </tbody>
                                                  </table>
                                                  
                                              </td>
                                          </tr>
                                      </tbody></table>
                                  </td>
                              </tr>
                              @php $counter++; @endphp
                              @endif
                            @endforeach
                            <!-- <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%" style="border-bottom:1px solid #333">
                                        <tbody><tr>
                                            <td style="padding:5px;font:bold 14px tahoma;font-family:arial;color:#333;margin:0">
                                                <strong>
                                                    
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr> -->
                            
                            
                            <!-- <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                
                                                <table width="170" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="30%">
                                                                               <span style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:15px;display:inline-block;color:#4a4a4a">Additional Information </span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                
                                                <table width="370" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding-bottom:5px">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                    
                                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:15px;margin:0;padding-top:3px;padding-bottom:3px;color:#4a4a4a">Hotel Policy: </p><br>
                                                                    
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr> -->
                            
                            
                            <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                
                                                <table width="170" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="30%">
                                                                               <span style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:15px;display:inline-block;color:#4a4a4a">Cancellation Policy </span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                
                                                <table width="370" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding-bottom:5px">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                    
                                                                    
                                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:15px;margin:0;padding-top:3px;padding-bottom:3px;color:#4a4a4a">{{ $booking->request_data['bookingData'][0]['CancellationPolicy'] }}</p><br>
                                                                    
                                                                    
                                                                    
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            
                            
                            <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:15px 0">
                                                
                                                <table width="170" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding:5px 0">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="30%">
                                                                               <span style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:15px;display:inline-block;color:#4a4a4a">Hotel Policy </span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                     </tbody>
                                                </table>
                                                
                                                
                                                <table width="370" align="left" border="0" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="100%" align="left" style="padding-bottom:5px">
                                                                <table width="100%" cellspacing="0" cellpadding="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                    
                                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:15px;margin:0;padding-top:3px;padding-bottom:3px;color:#4a4a4a">{!!html_entity_decode( $hotel_data['hotel_policy']) !!}</p><br>
                                                                    
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            
                            
                            
                            
                            
                            <tr>
                                <td width="100%" style="padding:0 30px">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td width="100%" style="border-bottom:1px solid #d8d8d8;padding:10px 0">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" align="left">
                                                        <tbody>
                                                            <tr>
                                                                <td width="100%" style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:15px;color:#5c5c5c" valign="top">***NOTE***: Any increase in the price due to taxes will be borne by you and payable at the hotel.
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- <tr>
                                <td style="padding:0 30px" width="100%">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="100%" style="padding:20px 0">
                                                   <p style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:16px;color:#4a4a4a;font-weight:bold;line-height:25px;text-align:left;margin-top:15px;font-weight:bold;margin-bottom:0;display:inline-block;padding:0">Need Help?</p>

                                                    
                                                        
                                                        <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:15px;line-height:25px;margin-top:0;padding:0">
                                                          For any questions related to the property, you can contact Hotel Shiraaz directly at: <a href="tel:%2001725066734" style="text-decoration:underline!important" rel="noreferrer" target="_blank">01725066734</a>
                                                            
                                                                / <a href="tel:%209256222224" style="text-decoration:underline!important" rel="noreferrer" target="_blank">9256222224</a>
                                                            
                                                        
                                                        
                                                            or <a href="https://mail.google.com/mail/?view=cm&amp;tf=1&amp;tohotelshiraaz@yahoo.com" style="text-decoration:underline!important" rel="noreferrer" target="_blank">hotelshiraaz@yahoo.com</a>
                                                        
                                                        </p>
                                                    
                                                        
                                                          <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:15px;line-height:15px;margin-top:0;padding:0">
                                                              If you would like to change or upgrade your reservation, visit
                                                              <a href="https://go.ibi.bo/W1E0UfGIyP" style="text-decoration:underline!important" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://go.ibi.bo/W1E0UfGIyP&amp;source=gmail&amp;ust=1607670819067000&amp;usg=AFQjCNGxsqne3k_NXw5whEAhgXIcRa6_3g">goibibo.com</a></p>
                                                              <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:15px;line-height:15px;margin-top:0;padding:0">
                                                                  <a href="http://www.goibibo.com/terms-and-conditions/?utm_source=Mailer&amp;utm_medium=HotelEticket&amp;utm_campaign=BookingPolicies" style="text-decoration:underline!important" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://www.goibibo.com/terms-and-conditions/?utm_source%3DMailer%26utm_medium%3DHotelEticket%26utm_campaign%3DBookingPolicies&amp;source=gmail&amp;ust=1607670819067000&amp;usg=AFQjCNEzZCwLwWscB_pAboom69EtC43GEA">Click here</a> to see booking policies.</p>
                                                                
                                                                    
                                                                
                                                      
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                               
                                   <td width="100%" style="padding:30px"><a href="http://www.goibibo.com/offers/qna/" style="width:100%;float:left" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://www.goibibo.com/offers/qna/&amp;source=gmail&amp;ust=1607670819067000&amp;usg=AFQjCNH7M_n46mBWqQeRt0yz7MVoq76GGw"><img src="https://ci4.googleusercontent.com/proxy/sF3p6zqzcRqT4fs-AkGncK-R0xcz5i7IfV_giCEnLenH_iNh9tps9QqIBq1DckP6yUhVwnQd3zMR3JNlG3kr=s0-d-e1-ft#https://gos3.ibcdn.com/banner-1450158446.jpg" style="width:100%;float:left" width="100%" class="CToWUd"></a></td>
                               
                            </tr> -->

                            

                    </tbody></table>
                </td>
            </tr>
        </tbody>
    </table>
    
        <!-- 
            <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
                <tbody>
                    <tr>
                        <td>
                            <table width="630" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                                <tbody>
                                    <tr>
                                        <td width="100%" style="padding:0">
                                                <table align="center" bgcolor="#FFFFFF" border="0" cellspacing="0" cellpadding="0" width="100%" style="text-align:center;padding-top:20px;padding-bottom:10px">
  <tbody><tr>
    <td style="width:100%;display:inline-block">
      <a href="https://www.myntra.com/growth/qrclaim/gommt_aug" style="width:570px;height:131px;display:inline-block" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.myntra.com/growth/qrclaim/gommt_aug&amp;source=gmail&amp;ust=1607670819067000&amp;usg=AFQjCNGz0K15Dr5BeIDLgPCM0QTXbq_EdA">
        <img src="https://ci6.googleusercontent.com/proxy/NcqtmOovcKbd65UrSdVU-e9dVy1oUwjrgKDzkHXY0pLn_-VnpW0ggp5EPHqYuldph9XoLEP90GwSghh7eb96=s0-d-e1-ft#https://gos3.ibcdn.com/myntra-1567079294.jpg" width="100%" height="100%" class="CToWUd">
      </a>
    </td>
  </tr>
</tbody></table>
                                        </td>
                                     </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table> -->
        
    
    
        
            <!-- <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
                <tbody>
                    <tr>
                        <td>
                            <table width="630" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                                <tbody>
                                    <tr>
                                        <td width="100%" style="padding:10px 30px">
                                                <a href="https://www.goibibo.com/cars/?utm_source=email&amp;utm_medium=hotel_booking_email&amp;utm_campaign=hotel_booking" style="width:100%;float:left" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.goibibo.com/cars/?utm_source%3Demail%26utm_medium%3Dhotel_booking_email%26utm_campaign%3Dhotel_booking&amp;source=gmail&amp;ust=1607670819067000&amp;usg=AFQjCNGec4RHmhjezed9DMUaE39WvImpgQ"><img src="https://ci5.googleusercontent.com/proxy/J1tTUXFjSFBo0ueNJm2AyByCxCP__XFciSB7S0HNyXFEfbh7kX3C5SL03SF3wEJIBFmvSbmsJxOsZXrq=s0-d-e1-ft#https://gos3.ibcdn.com/img-1509536110.jpg" style="width:100%;float:left" width="100%" class="CToWUd"></a>
                                        </td>
                                     </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table> -->
        
    

    
    
        
    

        
        
    <!-- <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
            <tbody>
                <tr>
                    <td>
                        <table width="630" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                            <tbody>
                                <tr>
                                    <td width="100%" style="padding:30px 0">
                                        <table bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" align="center">
                                            <tbody>
                                            
                                                <tr>
                                                    
                                                    <td width="40">
                                                        <a href="https://go.ibi.bo/W1E0UfGIyP" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://go.ibi.bo/W1E0UfGIyP&amp;source=gmail&amp;ust=1607670819067000&amp;usg=AFQjCNGxsqne3k_NXw5whEAhgXIcRa6_3g"><img style="padding-right:5px;padding-left:20px" src="https://ci4.googleusercontent.com/proxy/1R-XSUpiOLxFKnpfxcZ5Awvozfabi_tsoWoWpVZVwnxPSG4FBI8KfkB-Iq6wITYdd_PhXzYY-oinTVFsTSHnEeRRpNQoMz7WUF8=s0-d-e1-ft#https://gos3.ibcdn.com/0g0cthsll10hf24idnkmchgl0006.png" class="CToWUd"></a>
                                                    </td>
                                                    <td style="padding-top:7px"><span style="float:left;font-weight:bold;font-family:Helvetica,arial,sans-serif;font-size:16px;color:#2962aa;margin-right:5px;display:inline-block"> goCare Support </span><span style="font-family:Helvetica,arial,sans-serif;font-weight:bold;font-size:16px;color:#5c5c5c;text-decoration:none;margin:0;display:inline-block">- It is FASTER to WRITE to US</span>
                                                        <span style="float:left;font-family:Helvetica,arial,sans-serif;font-size:15px;text-decoration:none;margin:0;padding-bottom:10px;width:100%;color:#5c5c5c">
                                                            <a href="https://go.ibi.bo/W1E0UfGIyP" style="color:#2962aa" rel="noreferrer" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://go.ibi.bo/W1E0UfGIyP&amp;source=gmail&amp;ust=1607670819067000&amp;usg=AFQjCNGxsqne3k_NXw5whEAhgXIcRa6_3g">Click Here</a> to tell us your issue OR call us at <a href="tel:%2018002081060" rel="noreferrer" target="_blank">1800 208 1060</a></span>
                                                    </td>
                                                    
                                                </tr>
                                  
                                            </tbody>
                                        </table>
                                    </td>
                                 </tr>
                          </tbody>
                     </table>
          </td>
        </tr>
      </tbody>
</table> -->


    <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable_lpg">
        <tbody>
            <tr>
                <td>
                    <table width="630" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                        <tbody>
                            <tr>
                                <td width="100%" style="padding:0 30px">
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody><tr>
                                            <td width="100%" style="padding:0px 0">
                                                
                                                    
                                                
                                                
                                                    
                                                
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                             </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

        
        
  
            
            <table width="100%" bgcolor="#eaeaea" cellpadding="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
            <tbody>
                <tr>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" valign="top" border="0" align="center" style="margin:0 auto;max-width:630px">
                            <tbody>
                                <tr>
                                    <td width="100%" bgcolor="#ffffff" style="border-top:#cccccc 1px solid;border-bottom-left-radius:7px;border-bottom-right-radius:7px;padding:20px 30px">
                                        <table width="235" align="left" border="0" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td style="float:left;padding-top:15px;padding-bottom:10px"><a href="#m_563889437479177681_m_5417551291647373652_m_-8544940822317955741_" style="outline:none;padding-right:4px;text-align:center" rel="noreferrer"><!-- <img src="https://ci3.googleusercontent.com/proxy/B24HK0ys_AdACvspJjoEXRAWfR0eSLTgu2hTYKQxYRQ7r3lUmaFt3LWk_1kNKCtM-6wmBRocklmvSyMSmYqd__-BAFwFjYW3=s0-d-e1-ft#http://mdb.ibcdn.com/cnbo25ufnh6ud322gp59k4mg000e.png" class="CToWUd"> --></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table bgcolor="#ffffff" width="320" align="left" valign="middle" cellpadding="0" cellspacing="0" border="0">
                                            <tbody>
                                                <tr>
                                                    <td width="100%" align="left" style="background:#ffffff" colspan="3">
                                                        <span style="color:#5d5d5d;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:bold;line-height:30px"> Book with tripheist mobile App</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><a href="#" style="outline:none;padding-right:4px" rel="noreferrer" target="_blank"  class="CToWUd"><img src="https://ci5.googleusercontent.com/proxy/V5EWFe8BwPKn6QunVRazxxvC1WZqynmPJg8q1cwo48PMsNcM8J3bEwKpBm8W4CiuDUAsjxUccO8zhS27QRGiiD7RiH9RYSF5ow=s0-d-e1-ft#http://gos3.ibcdn.com/meski1uccp6kf4csn3th7nuf002h.png" class="CToWUd"></a></td>
                                                    <td><a href="" style="outline:none;padding-right:4px" rel="noreferrer" target="_blank" ><img src="https://ci3.googleusercontent.com/proxy/b5Dih05ut6FJu3tVACcR--gO5AvzbERXrlAO9o3Trl52ZPBSPYvlzKY6Wn8G9gOKUKbPYD38LTPxyNrg9EzuvH-TUW3sSp9CpA=s0-d-e1-ft#http://gos3.ibcdn.com/nd779js8e53m1bta7shk48q9002a.png" width="86" class="CToWUd"></a></td>
                                                    <td><a href="#" style="outline:none" rel="noreferrer" target="_blank" ><img src="https://ci3.googleusercontent.com/proxy/iWmVKx4YX7TVQk3p-JAM5GzoXCjQxYNJZaUMcw06z9LEmPlUHfBGH6BNyD0MZcd3uJFuNTkCMqkdlCdnmd5hJB6ywXqyoIVQ6Q=s0-d-e1-ft#http://gos3.ibcdn.com/gkg707nth92v1fl0n6o698db0015.png" width="90" class="CToWUd"></a></td>
                                                </tr>
                                            </tbody>
                                       </table>                     
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

            
  
        
        
        <table width="100%" bgcolor="#eaeaea" cellpadding="0" cellspacing="0" border="0" id="m_563889437479177681m_5417551291647373652m_-8544940822317955741backgroundTable">
            <tbody>
                <tr>
                    <td>
                        <table width="630" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                            <tbody>
                                <tr>
                                    <td width="100%">
                                        <table width="630" cellpadding="0" cellspacing="0" border="0" align="center">
                                  <tbody>
                                                <tr>
                                                    <td align="center" valign="middle">
                                                    
                                                        <p style="font-family:Helvetica,arial,sans-serif;font-size:11px;padding:7px 0;color:#ababab;margin:0">Tripheist Group, #123, This Is, Our Address (India)</p>
                                                    
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table><div class="yj6qo"></div><div class="adL">
        
    </div></div>