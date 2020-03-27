<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="{{ URL('assets/video_assets/css/normalize.min.css') }}">
    <link rel="stylesheet" href="{{ URL('assets/video_assets/css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="{{ URL('assets/video_assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link href="{{ URL('public/assets/plugins/holdon/HoldOn.min.css') }}" rel="stylesheet" />
    <script src="{{ URL('public/assets/plugins/holdon/HoldOn.min.js') }}"></script>
</head>

<body style=" background-color: #d3d3d359; margin: 0px !important; ">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td class="logo" style="text-align: center;background-color:#1e88e5;padding: 20px;">
                <h1><a href="#" style="color:white; text-decoration: none; font-family: poppins; font-weight: initial;">Email Campaign</a></h1>
            </td>
        </tr>
    </table>
    <div class="main-container" style="background-color: #ffffff;padding: 30px;margin: 30px;">
        <div class="main wrapper clearfix" id="video_here">
            <video width="100%" controls class="myvideo" id="myVideo" style="height:600px;display: block;box-shadow: 0px 0px 8px #777777;">
                <source src="@if($link) {{ URL($link) }} @endif">
            </video>
        </div>
    </div>
    <!-- <div class="vd">
        <button class="playbtn" style="background-color:#07080800;border: none;border-radius: 50px;color:#ffffffd9;font-weight: 600;/*;position: relative;;/* bottom: 200px; */margin: 20px;font-family: helvetica;position: absolute;bottom: 44%;left: 45%;"><i class="fa fa-play-circle" style=" font-size: 90px; "></i>
        </button>
    </div> -->
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
        <tr>
            <td class="bg_light" style="text-align: right; ">
                <p style="font-size: 20px;color: #1e88e5;font-family: poppins;margin: 10px;text-decoration: underline;margin: 20px ;">Share It With Your Friends!</p>
            </td>
        </tr>
    </table>
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
        <tr>
            <td class="bg_light" style="text-align: center;">
                <p style="font-size: 18px;color:#828282; ;padding-bottom: 10px;padding-top: 10px;margin: 0px !important; font-family:poppins;padding: 10px;">You're receiving this video since you signed up to email campaign. If you'd like to, you can unsubscribe now but you'll miss our future updates!</p>
            </td>
        </tr>
    </table>
    <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
        <tr>
            <td class="bg_light" style="text-align: center;">
                <p style="font-size: 15px;color: #1e88e5;;padding-bottom: 10px;padding-top: 10px;margin: 0px !important; font-family:poppins;">© 2005-2019 Email Campaign All Rights Reserved</p>
            </td>
        </tr>
    </table>

     <script type="text/javascript">
        var vid = document.getElementById("myVideo");

        @if(empty($link))
            $(document).ready(function(){
                genrate_video();
            });
        @else
            $(document).ready(function(){
                send_video_duration();
            })
        @endif

        function genrate_video(){
            HoldOn.open({
                theme:"sk-rect",
                message:'Please wait... while we are preparing your video for first time..',
                textColor:"white"
            });
            $.ajax({
                url: '{{ route("genrate_video") }}',
                type:'POST',
                data:{"id":'{{ $_GET["id"] }}'},
                success: function(data){
                    HoldOn.close();
                    send_video_duration();
                    html = '<video width="60%" controls class="myvideo" id="myVideo" style="height:400px"><source src="'+data+'"></video>';
                    $('#video_here').html(html);
                }
            });
        }

        function getCurTime() { 
            time            = vid.currentTime;
            notification_id = '{{ Crypt::encrypt($notification_id) }}';
            $.ajax({
                url: '{{ route("update_video_play_timer") }}',
                type:'post',
                data:{'time':time,'notification_id':notification_id},
                success: function(data){

                }
            });
        } 

        function send_video_duration(){
            setInterval(function(){
                getCurTime();
            },1000);
        }
    </script>
</body>

</html>