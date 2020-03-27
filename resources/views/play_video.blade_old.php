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

        <script src="{{ URL('assets/video_assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <link href="{{ URL('public/assets/plugins/holdon/HoldOn.min.css') }}" rel="stylesheet" />
        <script src="{{ URL('public/assets/plugins/holdon/HoldOn.min.js') }}"></script>
        <style type="text/css">
            video{
                margin: 7% auto;
                display: block;
                box-shadow: 0px 0px 8px #777777;
            }
        </style>
    </head>
    <body>
        <!-- <div class="header-container">
            <header class="wrapper clearfix">
                <h1 class="title">h1.title</h1>
                <nav>
                    <ul>
                        <li><a href="#">nav ul li a</a></li>
                        <li><a href="#">nav ul li a</a></li>
                        <li><a href="#">nav ul li a</a></li>
                    </ul>
                </nav>
            </header>
        </div> -->

        <div class="main-container">
            <div class="main wrapper clearfix" id="video_here" >
                <video width="60%" controls class="myvideo" id="myVideo" style="height:400px">
                    <source src="@if($link) {{ URL($link) }} @endif">
                </video>
            </div>
        </div>

        <!-- <div class="footer-container">
            <footer class="wrapper">
                <h3>footer</h3>
            </footer>
        </div> -->
        
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
