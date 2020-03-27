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
     
     <div class="gt" style="
    max-width: 600px;
    margin: 0 auto;
    background-color: #ffffff;
    
">
      <div style="max-width: 600px; margin: 0 auto; /* border: 2px solid #1e88e5;*/ "class="email-container">
         <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;     background-color: white;">
            <tr  style="
    BACKGROUND-COLOR: white;
">
               <td valign="top" class="bg_white">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                     <tr>
                        <td class="logo" style=" text-align: center; padding-left: 10px;">
                           <h1><a href="#" style="color:#1e88e5; text-decoration: none;   font-family: poppins;
    font-weight: initial;">Email Campaign</a></h1>
                        </td>
                     </tr>
                  </table>
               </td>
            </tr>
            
            
            
           
            <tr>
               <td valign="middle" class="bg_white" >
               @if($template->final_video)
            
                  <table style="margin-right: 0px !important;width: 100%; /* text-align: center; */">
                     <tr>
                        <td>
                           <div class="text" style=" text-align: center; margin-left: 10px; margin-right: 10px;">
                             
                       
                                 
                                    <div class="play_thumb" style="/*background-color: #d3d3d35c;padding-bottom: 40px; padding-top: 44px; border-radius: 5px;"*/ >
                                      <img src="{{ URL($thumb) }}"style="width: 100%;">
                                       
                                    </div>
                           
                           <a href="{{ route('open_video',['id'=>Crypt::encrypt($message_id)]) }}">
                           <button class="playbtn" style="background-color: #1e88e5;border: none;width: 30%;padding: 10px;border-radius: 50px;color: white;font-weight: 600;position: relative;bottom: 150px;RIGHT: 5PX;margin: 20px;font-family: helvetica;box-shadow:1px 3px 7px #2f2f2f;font-size: 18px;">Watch Video</button>
                           
                           
                                 </a>
                         <div class="tran" style="position:relative;">
                         <div class="triangle-up" style= "width: 0;height: 0;border-left: 25px solid transparent;border-right: 25px solid transparent;border-bottom: 50px solid #fff; position:absolute;bottom: 80px;right: 48%;"></div>
                         </div>
                              <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #eef5f9; border-radius: 10px; margin-top: -70px;">
                                       <tr>
                                          <td class="text-services" style="text-align: justify; padding: 20px;">
                                             <div class="heading-section">
                                                
                                                <p style="font-size: 22px;font-family: poppins; color:#868686;     text-align: center;">
                                                   {{ $data }}
                                                </p>
                                             </div>
                                          </td>
                                
                                       </tr>
                              <tr>
                           <td class="bg_light" style="text-align: center;">
                           <p style="font-size: 20px;padding: 20px;color: #868686;   font-family:poppins; margin-top: 0px;">Thank you</p>
                              </td>
                              </tr>
                                    </table>
                       
                       
                       
                       
                           </div>
                        </td>
                     </tr>
                  </table>
               @endif
              
               </td>
            </tr>
           
         </table>
       
       
       
       <tr style="
    BACKGROUND-COLOR:white;
">
               <td class="bg_white">
                  
               </td>
            </tr>
       
       
       
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;background-color:white;padding-top: 30px;">
            <tr>
               <td class="bg_light" style="text-align: center; color: #585858;">
                  <i class="fa fa-facebook-square"></i> <i class="fa fa-instagram"></i> <i class="fa fa-twitter-square"></i>
               </td>
            </tr>
         </table>
       
       
       
      
       
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto; background-color:white;">
            <tr>
               <td class="bg_light" style="text-align: center;">
                  <p style="font-size: 15px;color: #8c8c8c;
    background-color: #ffffff;font-family:poppins;">© 2005-2011 Email Campaign All Rights Reserved</p>
               </td>
            </tr>
         </table>
      </div>
     </div>
   </center>
</body>

</html>