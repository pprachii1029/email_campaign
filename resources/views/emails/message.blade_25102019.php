<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="x-apple-disable-message-reformatting">
   <title></title>
   <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #222222;">
   <center style="width: 100%; background-color: #f1f1f1;">
      <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;</div>
      <div class="gt" style=" max-width: 600px; margin: 0 auto; background-color: #ffffff; ">
         <div style="max-width: 600px; margin: 0 auto; /* border: 2px solid #1e88e5;*/ " class="email-container">
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; background-color: white;">
               <tr style=" BACKGROUND-COLOR: white; ">
                  <td valign="top" class="bg_white">
                     <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                           <td class="logo" style="/* text-align: center; */padding-left: 10px;">
                              <h1><a href="#" style="color:#1e88e5; text-decoration: none; font-family: poppins; font-weight: initial;">Email Campaign</a></h1>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td valign="middle" class="bg_white">
                     @if($template->final_video)
                     <table>
                        <tr>
                           <td>
                              <div class="text" style="padding: 0 2.5em; text-align: center;">
                                 <p style=" margin: 0px !important; ">
                                    <a href="{{ route('open_video',['id'=>Crypt::encrypt($message_id)]) }}">
                                       <div class="play_thumb" style=" background-color: #d3d3d35c; padding-bottom: 40px; padding-top: 44px; border-radius: 5px; ">
                                          <img src="{{ URL($thumb) }}" style="box-shadow: 0px 0px 8px #777777; width: 80%;">
                                       </div>
                                       <div class="vd">
                                          <button class="playbtn" style="background-color:#07080800;border: none;border-radius: 50px;color:#ffffffd9;font-weight: 600;/*;position: relative;;/* bottom: 200px; */margin: 20px;font-family:poppins;position: absolute;bottom: 60%;left: 46%;"><i class="fa fa-play-circle" style=" font-size: 90px; "></i>
                                          </button>
                                       </div>
                                    </a>
                                 </p>
                              </div>
                           </td>
                        </tr>
                     </table>
                     @endif
                  </td>
               </tr>
            </table>
            <tr style=" BACKGROUND-COLOR:white; ">
               <td class="bg_white">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                     <tr>
                        <td class="bg_light email-section" style="padding: 0; width: 100%;">
                           <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                              <tr>
                                 <td valign="middle" width="100%">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                       <tr>
                                          <td class="text-services" style="text-align: justify; padding: 20px;">
                                             <div class="heading-section">
                                                <p style="font-size: 19px;font-family: poppins; color:#868686;">{{ $data }}</p>
                                             </div>
                                          </td>
                                       </tr>
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; background-color: white;">
               <tr>
                  <td class="bg_light" style="text-align: center;">
                     <p style="font-size: 20px; background-color: white; padding: 20px; color: #868686; font-family: helvetica;">Thankyou</p>
                  </td>
               </tr>
            </table>
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; background-color:white;">
               <tr>
                  <td class="bg_light" style="text-align: center;">
                     <p style="font-size: 15px;color: #8c8c8c; background-color: #ffffff;font-family:poppins;">© 2005-2011 Email Campaign All Rights Reserved</p>
                  </td>
               </tr>
            </table>
         </div>
      </div>
   </center>
</body>

</html>