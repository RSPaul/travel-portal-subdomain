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
                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:15px;color:#9b9b9b;margin:0;margin-bottom:5px">Transaction ID: <span style="color:#000000;font-weight:bold;font-size:18px"><?= $payment->txn_id ?></span></p>
                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:12px;color:#9b9b9b;margin:0">Payment Made on: <?= date("d-m-Y H:i",strtotime($payment->created_at)) ?></p>
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
                                    <p></p>
                                    <table width="100%" align="left" cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table width="100%" cellpadding="15" bgcolor="#005dbd" cellspacing="0" border="0" align="center" style="padding-top:2em;border-bottom-left-radius:7px;border-bottom-right-radius:7px;border-top-left-radius:7px;border-top-right-radius:7px;border-bottom:1px #cccccc;margin:0 auto">
                                                        <tbody>
                                                            <tr>
                                                                <td width="100%" align="center">
                                                                    <h1 style="font-family:arial,sans-serif;font-weight:normal;font-size:30px;color:#fffff0;text-align:left;line-height:1.0">You Successfully made payment!</h1>
                                                                    <h1 style="font-family:arial,sans-serif;font-weight:normal;font-size:30px;color:#fffff0;text-align:left;line-height:1.0">Received Amount: <?= $payment->paid_amount; ?><?= $payment->currencyCode; ?></h1>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <p style="font-family:Helvetica,arial,sans-serif;font-size:16px;color:#4a4a4a;font-weight:bold;line-height:25px;text-align:left;margin-top:15px;margin-bottom:0;display:inline-block;padding:0">Dear <?= $user->name; ?>!</p>
                                                    <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                        ⇒ We have received your payment for Booking Amount <?= $payment->booking_amount; ?></b>.
                                                    </p>
                                                    <p style="color:#4a4a4a;font-family:Helvetica,arial,sans-serif;font-size:16px;line-height:25px;margin-top:15px;margin-bottom:0px;padding:0">
                                                        Total Amount Due:<?= round(($payment->booking_amount - $payment->total_paid), 2); ?> <?= $payment->currencyCode ?>.</b>
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
                                                <td style="float:left;padding-top:15px;padding-bottom:10px"><a href="#m_563889437479177681_m_5417551291647373652_m_-8544940822317955741_" style="outline:none;padding-right:4px;text-align:center" rel="noreferrer">
<!-- <img src="https://ci3.googleusercontent.com/proxy/B24HK0ys_AdACvspJjoEXRAWfR0eSLTgu2hTYKQxYRQ7r3lUmaFt3LWk_1kNKCtM-6wmBRocklmvSyMSmYqd__-BAFwFjYW3=s0-d-e1-ft#http://mdb.ibcdn.com/cnbo25ufnh6ud322gp59k4mg000e.png" class="CToWUd"> --></a></td>
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
    </table>
    <div class="yj6qo"></div>
    <div class="adL">
    </div>
</div>