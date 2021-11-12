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
   <table width="600" border="0" cellpadding="4" cellspacing="0" style="border:1px solid #d9deee;border-collapse:collapse;font-size:12px;font-family:Arial,Sans-Serif">
      <tbody>
         <tr>
            <td colspan="3"><b>Dear Partner,</b><br><br> 
                @if($searchData['JourneyType'] == '2')
                    As per your request, we are forwarding itineraries from  {{$searchData['from']}} ({{$searchData['origin']}}) - {{$searchData['to']}} ({{$searchData['destination']}}) on {{ date('l, F d Y', strtotime($searchData['departDate'])) }}, return {{$searchData['to']}} ({{$searchData['destination']}}) - {{$searchData['from']}} ({{$searchData['origin']}})  on {{ date('l, F d Y', strtotime($searchData['returnDate'])) }}  
                @else
                    As per your request, we are forwarding itineraries from  {{$searchData['from']}} ({{$searchData['origin']}}) - {{$searchData['to']}} ({{$searchData['destination']}}) on {{ date('l, F d Y', strtotime($searchData['departDate'])) }}
                @endif
                <br><br></td>
         </tr>
         <tr style="background:#dee4f6;border:1px solid #d9deee">
            <td colspan="2"><b>
                <a href="{{env('APP_URL')}}/flights?token={{rand()}}&JourneyType={{$searchData['JourneyType']}}&origin={{$searchData['origin']}}&from={{$searchData['from']}}&destination={{$searchData['destination']}}&to={{$searchData['to']}}&departDate={{$searchData['departDate']}}&returnDate={{$searchData['returnDate']}}&travellersClass={{$searchData['travellersClass']}}&referral={{$searchData['referral']}}&adultsF={{$searchData['adultsF']}}&childsF={{$searchData['childsF']}}&infants={{$searchData['infants']}}&FlightCabinClass={{$searchData['FlightCabinClass']}}&DirectFlight={{$searchData['DirectFlight']}}&OneStopFlight={{$searchData['OneStopFlight']}}&results={{$searchData['results']}}">{{$searchData['from']}} ({{$searchData['origin']}}) to {{$searchData['to']}} ({{$searchData['destination']}})</a>
                </b></td>
            <td style="text-align:right"><b> All | {{ $searchData['travellersClass'] }} </b></td>
         </tr>
         @foreach($flights as $flight)
         <tr>
            <td colspan="3" style="padding:10px">
               <table border="0" cellpadding="4" cellspacing="0" style="border:1px solid #d9deee;border-collapse:collapse" width="100%">
                  <tbody>
                     <tr style="background:#dee4f6">
                        <td width="48%" colspan="2"><b>{{ $flight['type'] }}</b></td>
                        <td width="48%" style="text-align:right"><b>Fare : {{ $searchData['currency'] }} {{ round($flight['price'] + ($searchData['iniscomm'] / 100 * $flight['price']) + ($searchData['conversion'] / 100 * ($flight['price'] + ($searchData['iniscomm'] / 100 * $flight['price']))),2) }} 
                        </b></td>
                     </tr>
                     <tr style="border-top:1px solid #d9deee">
                        <td width="25%"><img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata=@{{$flight['flightC']}}" alt="UK" width="30" height="30" class="CToWUd"></td>
                        <td width="40%"><b>Depart:</b> {{ $flight['depart'] }}</td>
                        <td width="35%" style="text-align:right"><b>Arrive:</b> {{ $flight['land'] }}</td>
                     </tr>
                     <tr>
                        <td style="padding-bottom:10px"><b>{{ $flight['flightNo'] }} - {{ $flight['flightC'] }}</b></td>
                        <td style="padding-bottom:10px"><b>Time:</b> {{ date('H:i:s', strtotime($flight['departT'])) }}</td>
                        <td style="text-align:right;padding-bottom:10px"><b>Time:</b> {{ date('H:i:s', strtotime($flight['landT'])) }}</td>
                     </tr>
                     <tr style="border:1px solid #d9deee">
                        <td colspan="3" style="text-align:right"><b>Total Flight Time:</b> {{ intdiv($flight['duration'], 60).'h '. ($flight['duration'] % 60) }} m</td>
                     </tr>
                  </tbody>
               </table>
            </td>
         </tr>
         @endforeach
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
                                    <td style="float:left;padding-top:15px;padding-bottom:10px">
                                       <a href="#m_563889437479177681_m_5417551291647373652_m_-8544940822317955741_" style="outline:none;padding-right:4px;text-align:center" rel="noreferrer">
                                          <!-- <img src="https://ci3.googleusercontent.com/proxy/B24HK0ys_AdACvspJjoEXRAWfR0eSLTgu2hTYKQxYRQ7r3lUmaFt3LWk_1kNKCtM-6wmBRocklmvSyMSmYqd__-BAFwFjYW3=s0-d-e1-ft#http://mdb.ibcdn.com/cnbo25ufnh6ud322gp59k4mg000e.png" class="CToWUd"> -->
                                       </a>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           <table bgcolor="#ffffff" width="320" align="left" valign="middle" cellpadding="0" cellspacing="0" border="0">
                              <tbody>
                                 <tr>
                                    <td width="100%" align="left" style="background:#ffffff" colspan="3">
                                       <span style="color:#5d5d5d;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:bold;line-height:30px"> Book with Tripheist mobile App</span>
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
   </table>
   <div class="yj6qo"></div>
   <div class="adL">
   </div>
</div>