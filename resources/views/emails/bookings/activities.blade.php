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
                                                        <img width="120" border="0" alt="" style="display:block;border:none;outline:none;text-decoration:none;width: 120px;" src="https://tripheist.com/images/logo.png" class="CToWUd">
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


<table width="600px" cellpadding="0" cellspacing="0" border="0" style="border:3px solid #d9deee;font-family:Arial,Sans-Serif;font-size:13px">
   <tbody>
      <tr>
         <td>
            <b>Dear Partner,</b><br>
            As per your request, we are forwarding itineraries for {{str_replace('+', ' ', $searchData['city_name'])}}.
         </td>
      </tr>
      <tr>
         <td style="padding:.8em;background:#e8ecf7;border-bottom:2px solid #e4e4e4">
            <table cellpadding="2" cellspacing="0" border="0" width="100%" style="font-family:Arial,Sans-Serif">
               <tbody>
                  <tr>
                     <td width="20%"><b>City : </b></td>
                     <td align="left">{{str_replace('+', ' ', $searchData['city_name'])}}</td>
                  </tr>
                  <tr>
                     <td><b>Check In : </b></td>
                     <td align="left">{{$searchData['travelstartdate']}}</td>
                     <td><b>Travellers : </b></td>
                     <td align="left">{{$searchData['travellersClass']}}</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
        @foreach($activities as $activity)
      <tr>
         <td style="padding:.8em .8em 0">
            <table cellpadding="5" cellspacing="0" border="0" style="font-family:Arial,Sans-Serif;background:#fbfbfb;border:1px solid #d0d0d0">
               <tbody>
                  <tr>
                    <td width="20%" style="font-size:1.4em;font-weight:bold">
                        <img src="{{$activity['ImageList'][0]}}" width="150" height="150">
                    </td>

                     <td width="60%">
                        <h2 style="color:#0e60a7;margin:0px;padding:0px;font-size:1.3em">
                        <a href="{{env('APP_URL')}}/activities?token={{rand()}}&travelstartdate={{$searchData['travelstartdate']}}&city_name={{$searchData['city_name']}}&city_act_id={{$searchData['city_act_id']}}&currency_code_act={{$searchData['currency_code_act']}}&travellersClass={{$searchData['travellersClass']}}&referral={{ isset($agent['referal_code']) ? $agent['referal_code'] : '0'}}&adultsCCA={{$searchData['adultsCCA']}}&childsCCA={{$searchData['childsCCA']}}">{{$activity['SightseeingName']}}</a>
                        </h2>
                     </td>
                     <td valign="top" align="right" style="font-size:1.4em;font-weight:bold">{{$searchData['currency']}} 
                      {{ round($activity['Price']['OfferedPriceRoundedOff'] + ( $searchData['commisioninis'] / 100 * $activity['Price']['OfferedPriceRoundedOff'] ) + ($searchData['conversion'] / 100 * ($activity['Price']['OfferedPriceRoundedOff'] + ( $searchData['commisioninis'] / 100 * $activity['Price']['OfferedPriceRoundedOff'] ))), 2) }}
                     </td>
                  </tr>
                  <tr>
                     <td colspan="2">
                        <dl>
                           <dt style="font-weight:bold">About Tour.</dt>
                        </dl>
                        <p style="margin:.5em 0"></p>
                        <p>Summary : {!!html_entity_decode($activity['TourSummary'])!!}</p>
                        <p></p>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
      @endforeach
      <tr>
         <td style="padding:.8em;color:#ff0000">
            **General Notification : Prices shown are subject to change at the time of booking.
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