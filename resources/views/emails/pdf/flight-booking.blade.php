<div style="font-family:Arial,Helvetica,sans-serif;margin:0;padding:0">
<table class="m_7001975098023456892deviceWidth" width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
<tbody>
<tr>
<td>
<table class="m_7001975098023456892deviceWidth" style="margin:0 auto;font-family:Arial,Helvetica,sans-serif;width:850px" align="center" cellpadding="0" cellspacing="0" border="0" width="850">
<tbody>
<tr>
<td style="border:1px solid #ccc;padding-top:35px;margin:0 auto;padding-bottom:30px;background-repeat:no-repeat">
<table class="m_7001975098023456892deviceWidth" style="margin:0 auto" align="center" cellpadding="0" cellspacing="0" border="0">
<tbody>
<tr>
<td style="font-size:35px;line-height:40px;font-family:Arial,Helvetica,sans-serif;padding-bottom:1px;font-weight:700;color:#000000;width:534px"> YOUR TICKET
</td>
</tr>
<tr>
<td style="padding-left:4px;padding-bottom:10px;font-size:18px;line-height:18px;color:#6f6f6f;font-family:Arial,Helvetica,sans-serif">                                                    TRIPHEIST BOOKING ID: {{ $booking->booking_id }}                                                </td>
</tr>
<tr>
<td style="padding-left:4px;padding-bottom:10px;font-size:18px;line-height:18px;color:#6f6f6f;font-family:Arial,Helvetica,sans-serif">                                                    BOOKING DATE: {{ $booking->created_at }} </td>
</tr>
<tr>
<td style="width:734px;background:#fff;border:1px solid #ccc;padding-top:34px;padding-bottom:15px;padding-left:30px;padding-right:30px">
   <table class="m_7001975098023456892deviceWidth" width="100%" border="0" cellpadding="0" cellspacing="0" align="right">
      <tbody>
         <tr>
            <td>
               <table border="0" cellpadding="0" cellspacing="0" width="534">
                  <tbody>
                     <tr>
                        <td style="text-align:center">                                                                                    <img alt="tripheist_logo" src="https://tripheist.com/images/logo.png" width="170" class="CToWUd">                                                                                </td>
                     </tr>
                     <tr>
                        <td style="height:18px"> &nbsp; </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td style="width:394px">
               <table cellspacing="0" cellpadding="0" border="0" width="394" align="center" style="margin:0 auto">
                  <tbody>
                     <tr>
                        <td style="padding-left:70px;padding-right:70px;width:394px;padding-top:19px">
                           <table cellspacing="0" cellpadding="0" border="0" width="394" align="center" style="margin:0 auto">
                              <tbody>
                                 <tr>
                                    <td style="width:151px">
                                       <table cellpadding="0" cellspacing="0" border="0">
                                          @if($booking->booking_ref != '')
                                          <tbody>
                                             <tr>
                                                <td style="color:#3d3d3d;font-family:Arial,Helvetica,sans-serif;font-size:75px;line-height:85px"> {{ $booking->request_data['travelData']['city_code_departure']}} </td>
                                             </tr>
                                             <tr>
                                                <td style="color:#9c9c9c;font-family:Arial,Helvetica,sans-serif;font-size:18px;line-height:20px;padding-left:10px">  {{ $booking->request_data['travelData']['to_start']}} </td>
                                             </tr>
                                          </tbody>
                                          @else
                                          <tbody>
                                             <tr>
                                                <td style="color:#3d3d3d;font-family:Arial,Helvetica,sans-serif;font-size:75px;line-height:85px"> {{ $booking->request_data['travelData']['city_code_arrival']}} </td>
                                             </tr>
                                             <tr>
                                                <td style="color:#9c9c9c;font-family:Arial,Helvetica,sans-serif;font-size:18px;line-height:20px;padding-left:10px">  {{ $booking->request_data['travelData']['main_start']}} </td>
                                             </tr>
                                          </tbody>
                                          @endif
                                       </table>
                                    </td>
                                    <td style="text-align:center;width:55px">
                                       <table cellpadding="0" cellspacing="0" border="0" width="46">
                                          <tbody>
                                             <tr>
                                                <td><img src="https://ci6.googleusercontent.com/proxy/tNZrKynkGrW1fiF9d8HNvbTlLlp7IwswWlJEjvnYYiuuD37x4lYHPunB0qKKiUjWLIhn-78ysC-wkEJU-AJ8QI1wzDMdK-nbo1J8DJZHNkY8vDK9Mt-chw=s0-d-e1-ft#http://www.mailmktg.makemytrip.com/images/mailer_blue_oneway-icon.png" alt="one_way_arrow" class="CToWUd"></td>
                                             </tr>
                                             <tr>
                                                <td style="height:21px">&nbsp;</td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                    <td style="width:151px">
                                       <table cellpadding="0" cellspacing="0" border="0">
                                          @if($booking->booking_ref != '')
                                          <tbody>
                                             <tr>
                                                <td style="color:#3d3d3d;font-family:Arial,Helvetica,sans-serif;font-size:75px;line-height:85px"> {{ $booking->request_data['travelData']['city_code_arrival']}} </td>
                                             </tr>
                                             <tr>
                                                <td style="color:#9c9c9c;font-family:Arial,Helvetica,sans-serif;font-size:18px;line-height:20px;padding-left:10px">  {{ $booking->request_data['travelData']['main_start']}} </td>
                                             </tr>
                                          </tbody>
                                          @else
                                          <tbody>
                                             <tr>
                                                <td style="color:#3d3d3d;font-family:Arial,Helvetica,sans-serif;font-size:75px;line-height:85px"> {{ $booking->request_data['travelData']['city_code_departure']}} </td>
                                             </tr>
                                             <tr>
                                                <td style="color:#9c9c9c;font-family:Arial,Helvetica,sans-serif;font-size:18px;line-height:20px;padding-left:10px">{{ $booking->request_data['travelData']['to_start']}}</td>
                                             </tr>
                                          </tbody>

                                          @endif
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="height:60px"> &nbsp;</td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td style="padding-left:40px;padding-right:40px;width:434px">
                           <table cellspacing="0" cellpadding="0" border="0" width="455" align="center" style="margin:0 auto">
                              <tbody>
                                 <?php foreach ($segments as $key => $value) { ?>
                                 
                                 <tr>
                                    <td>
                                       <table cellpadding="0" cellspacing="0" border="0" align="center">
                                          <tbody>
                                             <tr>
                                                <td style="color:#71d6f7;font-size:16px;line-height:18px;font-family:Arial,Helvetica,sans-serif;padding-bottom:4px;width:46px;text-align:center"><?php echo $value['Origin']['Airport']['AirportCode'];?></td>
                                                <td style="width:363px"></td>
                                                <td style="color:#71d6f7;font-size:16px;line-height:18px;font-family:Arial,Helvetica,sans-serif;padding-bottom:4px;width:46px;text-align:center"><?php echo $value['Destination']['Airport']['AirportCode'];?></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                                          <tbody>
                                             <tr>
                                                <td><img src="https://ci4.googleusercontent.com/proxy/206n8QgrkxyPMz6J2AB30cEqaRaGZTc_I03FLQyG4AsQYte5ApKUimAu0c0jjCRbACC4uDT6Z12-T7nLIflydyOD0UJ1WVIGnw=s0-d-e1-ft#http://www.mailmktg.makemytrip.com/images/blue-dot.png" alt="place" class="CToWUd"></td>
                                                <td style="vertical-align:middle;width:410px">
                                                   <table width="410px" border="0" cellpadding="0" cellspacing="0">
                                                      <tbody>
                                                         <tr>
                                                            <td style="background-color:#fff;height:1px;color:#fff;border-top:4px solid #fff;border-bottom:1px solid #71d6f7"></td>
                                                         </tr>
                                                         <tr>
                                                            <td style="background-color:white;height:3px;border-top:1px solid #ffffff"></td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                </td>
                                                <td><img src="https://ci4.googleusercontent.com/proxy/206n8QgrkxyPMz6J2AB30cEqaRaGZTc_I03FLQyG4AsQYte5ApKUimAu0c0jjCRbACC4uDT6Z12-T7nLIflydyOD0UJ1WVIGnw=s0-d-e1-ft#http://www.mailmktg.makemytrip.com/images/blue-dot.png" alt="place" class="CToWUd"></td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <table cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto">
                                          <tbody>
                                             <tr>
                                                <td style="padding-top:5px;font-size:18px;line-height:20px;color:#3d3d3d;font-family:Arial,Helvetica,sans-serif">{{ intdiv($value['Duration'], 60).'h '. ($value['Duration'] % 60) }} m</td>
                                             </tr>
                                             <tr>
                                                <td style="height:40px">&nbsp;</td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <?php } ?>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td style="width:725px;padding:0px;border-bottom:1px solid #d6d6d6;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6">
                           <table width="725" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="width:530px;border-top:1px solid #d6d6d6;border-bottom:1px solid #d6d6d6" colspan="3">
                                       <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse">
                                          <tbody>
                                             <tr>
                                                <td colspan="2" style="color:#6f6f6f;font-size:24px;line-height:27px;font-family:arial,helvetica,sans-serif;text-align:left;width:300px;padding-left:15px;padding-top:15px;padding-bottom:15px">1. PNR - <span style="color:#a3a3a3"> </span>                                                {{ $booking->pnr }}                                           </td>
                                                <td style="color:#6f6f6f;font-size:24px;line-height:27px;font-family:Arial,Helvetica,sans-serif;text-align:right;width:204px;padding-right:15px;padding-top:15px;padding-bottom:15px">
                                                   <p style="margin:0px;font-size:16px;line-height:20px;text-align:left">Dept Date:{{ $booking->request_data['travelData']['departure_date_arr']}}</p>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td colspan="3" style="padding:15px;text-align:right;border-bottom:1px solid #d6d6d6">                                                <a href="http://support.makemytrip.com/vouchers/flight/Barcode?pnr=B7M1WS" style="border:none;text-decoration:none" target="_blank" data-saferedirecturl="https://www.google.com/url?q=http://support.makemytrip.com/vouchers/flight/Barcode?pnr%3DB7M1WS&amp;source=gmail&amp;ust=1608011353090000&amp;usg=AFQjCNHRDvMmvNgcIX0u08RF6k-ing_psw">                                                    <img src="https://ci6.googleusercontent.com/proxy/qFbcAlKoQk9jvHYRfmli3BeyU3xUssVCLzKfFpIqQ1BjfRRrFllchgjVtWo3ZBkZj3M9Opi9Sf5bXMf8Vkkq5Mv_-PfYl9jE-fnffaT_t1GS_Gc=s0-d-e1-ft#http://support.makemytrip.com/vouchers/flight/Barcode?pnr=B7M1WS" alt="barcode" style="border:none;text-decoration:none" class="CToWUd">                                                </a>                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                  <?php foreach ($segments as $key => $value) { ?>
                                 <tr>
                                    <td style="width:208px;padding:15px;border-top:1px solid #d6d6d6">
                                       <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                          <tbody>
                                             <tr>
                                                <td style="color:#71d6f7;font-size:18px;line-height:22px;font-family:Arial,Helvetica,sans-serif;padding-bottom:15px;font-weight:700">                                                <?php echo $value['Origin']['Airport']['CityName'];?> - <?php echo $value['Destination']['Airport']['CityName']; ?>                                            </td>
                                             </tr>
                                             <tr>
                                                <td>
                                                   <p style="color:#3d3d3d;font-size:28px;line-height:32px;font-family:Arial,Helvetica,sans-serif;margin:0"><?php echo $value['Airline']['AirlineCode'];?> - <?php echo $value['Airline']['FlightNumber'];?></p>
                                                   <p style="color:#3d3d3d;font-size:24px;line-height:28px;font-family:Arial,Helvetica,sans-serif;margin:0"><?php echo $value['Airline']['AirlineName'];?></p>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                    <td style="width:115px;padding:15px;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6;border-top:1px solid #d6d6d6">
                                       <table cellpadding="0" cellspacing="0" border="0" width="115">
                                          <tbody>
                                             <tr>
                                                <td style="color:#71d6f7;font-size:18px;line-height:22px;font-family:Arial,Helvetica,sans-serif;padding-bottom:15px;font-weight:700">DEPART</td>
                                             </tr>
                                             <tr>
                                                <td>
                                                   <p style="color:#3d3d3d;font-size:20px;line-height:32px;font-family:Arial,Helvetica,sans-serif;margin:0">{{ date('Y-m-d' , strtotime($value['Origin']['DepTime'])) }} {{ date('H:i' , strtotime($value['Origin']['DepTime'])) }}</p>
                                                   <!-- <p style="color:#3d3d3d;font-size:24px;line-height:28px;font-family:Arial,Helvetica,sans-serif;margin:0"> PM </p> -->
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                    <td style="width:117px;padding:15px;border-top:1px solid #d6d6d6">
                                       <table cellpadding="0" cellspacing="0" border="0" width="117">
                                          <tbody>
                                             <tr>
                                                <td style="color:#71d6f7;font-size:18px;line-height:22px;font-family:Arial,Helvetica,sans-serif;padding-bottom:15px;font-weight:700">ARRIVE</td>
                                             </tr>
                                             <tr>
                                                <td>
                                                   <p style="color:#3d3d3d;font-size:20px;line-height:32px;font-family:Arial,Helvetica,sans-serif;margin:0">{{ date('Y-m-d' , strtotime($value['Destination']['ArrTime'])) }} {{ date('H:i' , strtotime($value['Destination']['ArrTime'])) }} </p>
                                                   <!-- <p style="color:#3d3d3d;font-size:24px;line-height:28px;font-family:Arial,Helvetica,sans-serif;margin:0"> AM </p> -->
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <?php } ?>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td style="width:702px;padding:15px;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6">
                           <table width="702" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:16px;width:320px;line-height:20px;text-align:left">                                    <span style="font-size:18px;line-height:20px;color:#71d6f7;font-family:Arial,Helvetica,sans-serif;font-weight:700">PASSENGERS</span> / {{ sizeof($booking->request_data['bookingData']['Passengers']) }} Adults                                </td>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:18px;width:180px;color:#71d6f7;line-height:20px;text-align:right;vertical-align:top;font-weight:700">E-TICKET NO.</td>
                                 </tr>
                                 @foreach($booking->request_data['bookingData']['Passengers'] as $k => $p)
                                 <tr>
                                    <td style="text-align:left;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:320px">
                                       <p style="color:#3d3d3d;font-size:24px;line-height:30px;font-family:Arial,Helvetica,sans-serif;margin:0">{{ $k + 1 }}. {{ $p['FirstName'] }} {{ $p['LastName'] }}</p>
                                    </td>
                                    <td style="text-align:right;vertical-align:top;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:180px">
                                       <p style="color:#a3a3a3;font-size:19px;line-height:28px;margin:0">{{ $booking->request_data['travelData']['ticket_id'][$k] }}</p>
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     <!-- For Meal and Baggage -->
                     @if(isset($booking->request_data['bookingData']['Passengers'][0]['Meal']))
                     <tr>
                        <td style="width:702px;padding:15px;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6">
                           <table width="702" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:16px;width:320px;line-height:20px;text-align:left">                                    <span style="font-size:18px;line-height:20px;color:#71d6f7;font-family:Arial,Helvetica,sans-serif;font-weight:700">PASSENGERS</span> / {{ sizeof($booking->request_data['bookingData']['Passengers']) }} Adults                                </td>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:18px;width:180px;color:#71d6f7;line-height:20px;text-align:right;vertical-align:top;font-weight:700">Meal</td>
                                 </tr>
                                 @foreach($booking->request_data['bookingData']['Passengers'] as $kM => $p)
                                 <tr>
                                    <td style="text-align:left;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:320px">
                                       <p style="color:#3d3d3d;font-size:24px;line-height:30px;font-family:Arial,Helvetica,sans-serif;margin:0">{{ $kM + 1 }}. {{ $p['FirstName'] }} {{ $p['LastName'] }}</p>
                                    </td>
                                    <td style="text-align:right;vertical-align:top;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:180px">
                                       @if(isset($booking->request_data['bookingData']['Passengers'][$kM]['Meal']['Description']))
                                       <p style="color:#a3a3a3;font-size:19px;line-height:28px;margin:0">{{ $booking->request_data['bookingData']['Passengers'][$kM]['Meal']['Description'] }}</p>
                                       @else
                                       <p style="color:#a3a3a3;font-size:19px;line-height:28px;margin:0">N/A</p>
                                       @endif
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     @endif
                     <!-- For Meal and Baggage -->
                     @if(isset($booking->request_data['bookingData']['Passengers'][0]['Seat']))
                     <tr>
                        <td style="width:702px;padding:15px;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6">
                           <table width="702" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:16px;width:320px;line-height:20px;text-align:left">                                    <span style="font-size:18px;line-height:20px;color:#71d6f7;font-family:Arial,Helvetica,sans-serif;font-weight:700">PASSENGERS</span> / {{ sizeof($booking->request_data['bookingData']['Passengers']) }} Adults                                </td>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:18px;width:180px;color:#71d6f7;line-height:20px;text-align:right;vertical-align:top;font-weight:700">Seat</td>
                                 </tr>
                                 @foreach($booking->request_data['bookingData']['Passengers'] as $kS => $p)
                                 <tr>
                                    <td style="text-align:left;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:320px">
                                       <p style="color:#3d3d3d;font-size:24px;line-height:30px;font-family:Arial,Helvetica,sans-serif;margin:0">{{ $kS + 1 }}. {{ $p['FirstName'] }} {{ $p['LastName'] }}</p>
                                    </td>
                                    <td style="text-align:right;vertical-align:top;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:180px">
                                       @if(isset($booking->request_data['bookingData']['Passengers'][$kS]['Seat']['Description']))
                                       <p style="color:#a3a3a3;font-size:19px;line-height:28px;margin:0">{{ $booking->request_data['bookingData']['Passengers'][$kS]['Seat']['Description'] }}</p>
                                       @else
                                       <p style="color:#a3a3a3;font-size:19px;line-height:28px;margin:0">N/A</p>
                                       @endif
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     @endif
                     <!-- Ends Here -->

                     <!-- Meal Baggae Array -->
                     @if(isset($booking->request_data['bookingData']['Passengers'][0]['MealDynamic']))
                     <tr>
                        <td style="width:702px;padding:15px;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6">
                           <table width="702" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:16px;width:320px;line-height:20px;text-align:left">                                    <span style="font-size:18px;line-height:20px;color:#71d6f7;font-family:Arial,Helvetica,sans-serif;font-weight:700">PASSENGERS</span> / {{ sizeof($booking->request_data['bookingData']['Passengers']) }} Adults                                </td>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:18px;width:180px;color:#71d6f7;line-height:20px;text-align:right;vertical-align:top;font-weight:700">Meal</td>
                                 </tr>
                                 @foreach($booking->request_data['bookingData']['Passengers'] as $kS => $p)
                                 @if(isset($booking->request_data['bookingData']['Passengers'][$kS]) && isset($booking->request_data['bookingData']['Passengers'][$kS]['MealDynamic']))
                                 @foreach($booking->request_data['bookingData']['Passengers'][$kS]['MealDynamic'] as $keypmDM => $value)
                                 <tr>
                                    <td style="text-align:left;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:320px">
                                       <p style="color:#3d3d3d;font-size:24px;line-height:30px;font-family:Arial,Helvetica,sans-serif;margin:0">{{ $kS + 1 }}. {{ $p['FirstName'] }} {{ $p['LastName'] }}</p>
                                    </td>
                                    <td style="text-align:right;vertical-align:top;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:180px">
                                       <p style="color:#a3a3a3;font-size:19px;line-height:28px;margin:0">Meal ({{ $value['Origin'] }} - {{ $value['Destination'] }}) {{ $value['AirlineDescription'] }} - {{ $value['Currency'] }} {{ $value['Price'] }}</p>
                                    </td>
                                 </tr>
                                 @endforeach
                                 @endif
                                 @endforeach
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     @endif
                     @if(isset($booking->request_data['bookingData']['Passengers'][0]['Baggage']))
                     <tr>
                        <td style="width:702px;padding:15px;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6">
                           <table width="702" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:16px;width:320px;line-height:20px;text-align:left">                                    <span style="font-size:18px;line-height:20px;color:#71d6f7;font-family:Arial,Helvetica,sans-serif;font-weight:700">PASSENGERS</span> / {{ sizeof($booking->request_data['bookingData']['Passengers']) }} Adults                                </td>
                                    <td style="font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;font-size:18px;width:180px;color:#71d6f7;line-height:20px;text-align:right;vertical-align:top;font-weight:700">Baggage</td>
                                 </tr>
                                 @foreach($booking->request_data['bookingData']['Passengers'] as $kS => $p)
                                 @if(isset($booking->request_data['bookingData']['Passengers'][$kS]) && isset($booking->request_data['bookingData']['Passengers'][$kS]['Baggage']))
                                 @foreach($booking->request_data['bookingData']['Passengers'][$kS]['Baggage'] as $keypmDM => $value)
                                 <tr>
                                    <td style="text-align:left;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:320px">
                                       <p style="color:#3d3d3d;font-size:24px;line-height:30px;font-family:Arial,Helvetica,sans-serif;margin:0">{{ $kS + 1 }}. {{ $p['FirstName'] }} {{ $p['LastName'] }}</p>
                                    </td>
                                    <td style="text-align:right;vertical-align:top;padding-top:15px;font-family:Arial,Helvetica,sans-serif;width:180px">
                                       <p style="color:#a3a3a3;font-size:19px;line-height:28px;margin:0">Baggage ({{ $value['Origin'] }} - {{ $value['Destination'] }}) {{ $value['Weight'] }} Kg - {{ $value['Currency'] }} {{ $value['Price'] }}</p>
                                    </td>
                                 </tr>
                                 @endforeach
                                 @endif
                                 @endforeach
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     @endif
                     <!-- Ends Here -->
                     <tr style="display: none;">
                        <td style="width:532px;border-top:1px solid #d6d6d6;border-bottom:1px solid #d6d6d6;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6">
                           <table cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="width:534px;padding:0;vertical-align:top">
                                       <table cellpadding="0" cellspacing="0" border="0" width="534">
                                          <tbody>
                                             <tr>
                                                <td style="padding:15px;width:534px;font-family:Arial,Helvetica,sans-serif;font-size:18px;line-height:20px;color:#a3a3a3;border-bottom:1px solid #d6d6d6;border-top:1px solid #d6d6d6;vertical-align:top" colspan="2">Check-In  <span style="color:#6f6f6f;font-size:18px;line-height:21px">2 hours before the departure time</span></td>
                                             </tr>
                                             <tr>
                                                <td style="width:534px;vertical-align:top">
                                                   <table cellpadding="0" cellspacing="0" border="0" width="534">
                                                      <tbody>
                                                         <tr>
                                                            <td style="font-size:18px;line-height:20px;font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;width:217px;padding:15px;border-right:1px solid #d6d6d6;border-top:1px solid #d6d6d6">
                                                               Terminal<p style="font-size:20px;line-height:20px;font-family:Arial,Helvetica,sans-serif;color:#6f6f6f;margin:0;padding-top:15px;padding-bottom:15px">--</p>
                                                            </td>
                                                            <td style="font-size:18px;line-height:20px;font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;width:218px;padding:15px;color:#a3a3a3;border-top:1px solid #d6d6d6">
                                                               Class<p style="font-size:20px;line-height:20px;font-family:Arial,Helvetica,sans-serif;color:#6f6f6f;margin:0;padding-top:15px;padding-bottom:15px"> --</p>
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
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td style="height:15px">&nbsp;</td>
         </tr>
         <tr>
            <td style="height:60px">&nbsp;</td>
         </tr>
         
         <tr>
            <td style="width:534px;border-bottom:1px solid #d6d6d6;padding-bottom:12px;padding-top:18px">
               <a href="{{ env('APP_URL') }}/user/e-ticket/flight/{{ $booking->booking_id }}" style="text-decoration:none" target="_blank" >
                  <table cellpadding="0" cellspacing="0" border="0" width="534">
                     <tbody>
                        <tr>
                           <td style="width:450px;color:#2f86eb;font-size:25px;line-height:27px;font-family:Arial,Helvetica,sans-serif">Download Ticket</td>
                           <td style="width:84px;text-align:right"><img src="https://ci5.googleusercontent.com/proxy/kiDVB53TUMMOvtZRtWWAWN1761ouYFyDlezSygpVJLzKb7RSp1DXcprJw_pp3pYR6g49zNwCZMQSjFH00VceeH7aUQccLfvcUVfTep0Cf556zKuO5Q=s0-d-e1-ft#http://www.mailmktg.makemytrip.com/images/mailer_download-icon.png" alt="download_icon" class="CToWUd"></td>
                        </tr>
                     </tbody>
                  </table>
               </a>
            </td>
         </tr>
         <tr>
            <td style="height:60px">&nbsp;</td>
         </tr>
         <tr>
            <td style="width:534px">
               <table cellpadding="0" cellspacing="0" border="0" width="534">
                  <tbody>
                     <tr>
                        <td style="font-size:30px;line-height:34px;color:#3d3d3d;font-family:Arial,Helvetica,sans-serif;padding-bottom:15px;font-weight:700">Baggage Allowance</td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td style="width:732px;border:1px solid #d6d6d6">
               <table cellpadding="0" cellspacing="0" border="0" width="732">
                  <tbody>
                     <tr>
                        <td style="background-color:#f9f9f9;color:#6f6f6f;font-size:16px;line-height:18px;padding:15px;font-family:Arial,Helvetica,sans-serif" colspan="5">ADULT</td>
                     </tr>
                     <tr>
                        <td style="font-size:16px;line-height:20px;padding:15px 12px;font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;width:102px;border-right:1px solid #d6d6d6;border-bottom:1px solid #d6d6d6;border-top:1px solid #d6d6d6;font-weight:700;vertical-align:top">SECTOR</td>
                        <td style="font-size:16px;line-height:20px;padding:15px 12px;font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;width:102px;border-right:1px solid #d6d6d6;border-bottom:1px solid #d6d6d6;border-top:1px solid #d6d6d6;font-weight:700;vertical-align:top">AIRLINE</td>
                        <td style="font-size:16px;line-height:20px;padding:15px 12px;font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;width:102px;border-right:1px solid #d6d6d6;border-bottom:1px solid #d6d6d6;border-top:1px solid #d6d6d6;font-weight:700;vertical-align:top">CLASS</td>
                        <td style="font-size:16px;line-height:20px;padding:15px;font-family:Arial,Helvetica,sans-serif;color:#a3a3a3;width:103px;border-bottom:1px solid #d6d6d6;font-weight:700;border-top:1px solid #d6d6d6" colspan="2">
                           <table cellspacing="0" cellpadding="0" border="0">
                              <tbody>
                                 <tr>
                                    <td style="margin:0px;line-height:19px;font-size:16px;color:#a3a3a3;font-weight:700">BAGGAGE</td>
                                 </tr>
                                 <tr>
                                    <td style="font-size:11px;line-height:14px;color:#a3a3a3">CABIN+CHECK-IN</td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     @foreach ($segments as $key => $s) 
                     <tr>
                        <td style="color:#6f6f6f;font-size:16px;line-height:20px;font-family:Arial,Helvetica,sans-serif;padding:12px 15px;border-right:1px solid #d6d6d6;vertical-align:top">{{  $s['Origin']['Airport']['CityCode']}} - {{  $s['Destination']['Airport']['CityCode']}}</td>
                        <td style="color:#6f6f6f;font-size:16px;line-height:20px;font-family:Arial,Helvetica,sans-serif;padding:12px 15px;border-right:1px solid #d6d6d6;vertical-align:top">{{  $s['Airline']['AirlineName']}}</td>
                        <td style="color:#6f6f6f;font-size:16px;line-height:20px;font-family:Arial,Helvetica,sans-serif;padding:12px 15px;border-right:1px solid #d6d6d6;vertical-align:top"> 
                           @if($s['CabinClass'] == 1)
                              Economy
                           @elseif($s['CabinClass'] == 2)
                              Premium Economy
                           @elseif($s['CabinClass'] == 3)
                              Business
                           @elseif($s['CabinClass'] == 4)
                              Premium Business
                           @elseif($s['CabinClass'] == 5)
                              First
                           @else
                              Economy
                           @endif


                        </td>
                        <td style="color:#6f6f6f;font-size:16px;line-height:20px;font-family:Arial,Helvetica,sans-serif;width:76px;text-align:center;vertical-align:top;padding:15px 0px;border-right:1px solid #d6d6d6"> {{  $s['CabinBaggage']}} </td>
                        <td style="color:#6f6f6f;font-size:16px;line-height:20px;font-family:Arial,Helvetica,sans-serif;width:76px;text-align:center;padding:15px 0px;vertical-align:top">{{  $s['Baggage']}}</td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td colspan="5" style="text-align:left;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6;border-top:1px solid #d6d6d6;color:#6f6f6f;font-size:12px;line-height:15px;font-family:Arial,Helvetica,sans-serif;padding-top:15px;padding-left:15px;padding-right:15px;padding-bottom:1px;vertical-align:central">                    CWA* - Check with Airline (Baggage details not available. Please check with the airline)                        <br>                                                The weight of the cabin baggage should not exceed 7 kgs (for IndiGo, the limit is 7 kgs including the laptop)                                        </td>
         </tr>
         <tr>
            <td colspan="5" style="text-align:left;border-left:1px solid #d6d6d6;border-right:1px solid #d6d6d6;border-bottom:1px solid #d6d6d6;color:#6f6f6f;font-size:12px;line-height:15px;font-family:Arial,Helvetica,sans-serif;padding-top:1px;padding-left:15px;padding-right:15px;padding-bottom:15px;vertical-align:central">                    The above data is indicative and may change without notification. Kindly contact the airline directly for the latest information on baggage rules and allowances.                </td>
         </tr>
         <tr>
            <td style="height:60px">&nbsp;</td>
         </tr>
         <tr>
            <td>
               <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse" width="734" align="center">
                  <tbody>
                     <tr>
                        <td>
                           <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                              <tbody>
                                 <tr>
                                    <td style="font-size:30px;font-weight:700;line-height:34px;color:#3d3d3d;padding:15px 0px;font-family:Arial,Helvetica,sans-serif">Customer Support </td>
                                 </tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                     <tr>
                        <td style="border:1px solid #ebebeb;padding:20px 15px;font-family:Arial,Helvetica,sans-serif;color:#616161;background:#f9f9f9">
                           <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                              <tbody>
                                 <tr>
                                    <td style="padding-bottom:25px">
                                       <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                          <tbody>
                                             <tr>
                                                <td style="text-align:left;vertical-align:top;font-size:26px;font-weight:700;line-height:22px;font-family:Arial,Helvetica,sans-serif;color:#2f2f2f">TripHeist Support</td>
                                             </tr>
                                          </tbody>
                                       </table>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td style="padding-bottom:10px">
                                       <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                          <tbody>
                                             <tr>
                                                <td style="width:130px;text-align:left;vertical-align:top;font-size:18px;line-height:22px;font-family:Arial,Helvetica,sans-serif;color:#616161;font-weight:bold"> Web </td>
                                                <td>
                                                   <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                                      <tbody>
                                                         <tr>
                                                            <td style="font-family:Arial,Helvetica,sans-serif;color:#616161;font-size:20px;line-height:24px"><a href="{{ env('APP_URL') }}" style="color:#5e9fea;text-decoration:none" target="_blank">tripheist.com </a>
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
                                    <td style="padding-bottom:10px">
                                       <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                          <tbody>
                                             <tr>
                                                <td style="width:130px;text-align:left;vertical-align:top;font-size:18px;line-height:24px;color:#616161;font-family:Arial,Helvetica,sans-serif;font-weight:bold">FAQs</td>
                                                <td style="width:400px;text-align:left;font-size:20px;line-height:24px">
                                                   <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                                      <tbody>
                                                         <tr>
                                                            <td style="padding-bottom:7px;font-size:20px;line-height:24px;font-family:Arial,Helvetica,sans-serif;color:#616161"><a href="{{ env('APP_URL') }}/help" style="color:#5e9fea;text-decoration:none" target="_blank">tripheist.com/help</a></td>
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
                                    <td style="padding-bottom:40px">
                                       <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                          <tbody>
                                             <tr>
                                                <td style="width:130px;text-align:left;color:#616161;vertical-align:top;font-size:18px;line-height:22px;font-family:Arial,Helvetica,sans-serif;font-weight:bold">TEL.</td>
                                                <td style="width:400px;text-align:left;font-size:22px;line-height:24px;color:#616161">
                                                   <table class="m_7001975098023456892deviceWidth" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                                      <tbody>
                                                         <tr>
                                                            <td style="padding-bottom:7px;font-family:Arial,Helvetica,sans-serif;font-size:20px;line-height:20px;color:#616161">1-123-456-789 (Toll Free) </td>
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
                                    <td style="padding-bottom:25px">
                                       <table cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse">
                                          <tbody>
                                             <tr>
                                                <td style="text-align:left;vertical-align:top;font-size:26px;font-weight:700;line-height:22px;font-family:Arial,Helvetica,sans-serif;color:#2f2f2f"></td>
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
            </td>
         </tr>
         <tr>
            <td style="width:734px">
               <table cellpadding="0" cellspacing="0" border="0" width="734">
                  <tbody>
                     <tr>
                        <td style="font-size:30px;line-height:34px;color:#3d3d3d;font-family:Arial,Helvetica,sans-serif;padding-bottom:15px;font-weight:700">Cancellation and Amendments </td>
                     </tr>
                     <tr>
                        <td style="width:732px;border:1px solid #d6d6d6">
                           <table width="732" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <!-- Cancellation Policy Html -->
                                 {!!html_entity_decode($farerules[0]['FareRuleDetail'])!!}
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         <tr>
            <td style="height:60px">&nbsp;</td>
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
</td>
</tr>
</tbody>
</table>
</div>