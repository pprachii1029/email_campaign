@extends('layouts.app') 

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="{{ URL('public/assets/owlcarousel/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ URL('public/assets/owlcarousel/owl.carousel.min.css') }}"> 
<link rel="stylesheet" href="{{ URL('public/assets/css/custom.css') }}" >
<link rel="stylesheet" href="{{ URL('public/assets/css/custom2.css') }}" >
<div class="recorder d-none" onclick="">
    <button type="button" class="record_btn" id="recorder"><i class="fa fa-microphone"></i></button>
    <p id="msg_box"></p>
    <p id="counter"></p>
</div>
<style>
    /*.draggable{
        height: 150px !important;
        padding: 5px 5px !important;
    }
    .draggable img{
        height: 150px !important;
        width: 100% !important;
    }
    .droppable{
        background-color: #fff;
    }
    .recorder{
        width: auto;
        max-width: auto;
    }
    .extra-div{
        height: 13px;
        background: #2c87e53d;
    }
    .video_div{
        width: 500px;
        margin: auto;
    }*/
    .rcrdbtn{
        background: transparent;
        border: transparent;
        padding: 20px 20px;
    }
    .d-none{
        display: none !important;
    }
</style>
<section id="content">
    <form method="POST" action="{{ route('make_video') }}" onsubmit="hold_on()">
      <div class="container-fluid title-bar">
        <div class="row">
          <div class="col-md-4 mt-2">
            <h3 class="page-title pl-3">Arrange Template</h3>
          </div>
          <div class="col-md-8 d-flex justify-content-end"></div>
        </div>
      </div>
      <div class="container-fluid main-side mt-4 mb-5 pb-5 ">
        <div class="row">
          <div class="col-md-12">
            <div class="text-center mt-5 ">
              <div class="row">
                <div class="owl-carousel">
                  @foreach($template as $key => $row)
                  <div class="boxarr">
                    <input type="hidden" value="{{ $row['video'] }}" name="video[]">
                    <input type="hidden" value="{{ $row['id'] }}" name="id[]">
                    @if($row['content']=='video')
                      <video width="100%" controls class="myvideo_outro_{{ $key }}" style="height:100%" duration="{{ get_duration($row['video']) }}">
                          <source src="{{ URL($row['video']) }}" >
                      </video>
                    @else
                      <video width="100%" controls class="myvideo_outro_{{ $key }}" style="height:100%" duration="{{ get_duration($row['video']) }}">
                          <source src="{{ URL($row['video']) }}" >
                      </video>
                    @endif

                    <div class="col-md-12 text-center counter">
                        <span id="counter_outro_{{ $key }}" style="font-size: 40px;text-align: center;display: block;"></span>
                    </div>


                    <div class="col-md-12">
                        <div class="col-md-6"> 
                            <input type="hidden" value="" id="outro_{{ $key }}_audio" name="audio[]">

                            <div class="recorder_outro_{{ $key }}" onclick="">
                                <button type="button" class="record_btn- rcrdbtn" id="recorder_outro_{{ $key }}">
                                    <i class="fa fa-microphone"></i>
                                </button>
                            </div>

                            <p id="msg_box_outro_{{ $key }}" style="display: none;"></p>
                            <p id="counter_box_outro_{{ $key }}"></p>
                        </div>

                        <div class="col-md-6"> 
                            <input type="hidden" value="" id="video_link_{{ $key }}" name="video_recorded[]">

                            <div class="video_recorder_{{ $key }}" onclick="">
                                <button type="button" class="rcrdbtn" id="video_recorder_{{ $key }}" recording="0">
                                    <i class="fa fa-camera" style="color: #1e88e5;font-size: 40px;"></i>
                                </button>
                            </div>
                        </div>
                    </div>  
                    
                    <div class="col-md-12 streamer_counter_{{ $key }}" style="display: none;"> 
                        <video width="100%"></video>
                    </div>










                    <div class="row d-flex justify-content-center mt-5">
                      <button type="button" class="btn preview-btn ml-5" style="margin: 0px !important;" onclick="retake_{{ $key }}()">RETAKE</button>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              <a>
                <input type="hidden" name="template_id" id="template_id" value="{{ $template_id }}">
                <button type="submit" class="btn preview-btn " style="margin-top: 30px; margin-right: 18%;" name="submit" value="save">Preview</button>
              </a>
            </div>
          </div>
        </div>
      </div>
  </form>
</section>
<script src="{{ URL('public/assets/owlcarousel/owl.carousel.min.js') }}"></script>
<script type="text/javascript">
  $('.owl-carousel').owlCarousel({
    items:4,
    lazyLoad:true,
    nav:true,
    loop:false,
    navRewind:false,
    margin:10,
    dots: true,
    responsiveClass:true,    
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:1,
            nav:false
        },
        1000:{
            items:2,
            nav:true,
            
        },
        1200:{
            items:3,
            nav:true,
        }
    }
});
</script>
<script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>

@for($i=0;$i<count($template);$i++)
<script type="text/javascript">
    var video_{{ $i }};
    var recorder_{{ $i }};
    var stream_{{ $i }};
    var recoder_duration_{{ $i }};

    $("#video_recorder_{{ $i }}").click(async function(){
        btn = this;
        if($(btn).attr('recording')==0){
            
            video_{{ $i }} = $('.streamer_counter_{{ $i }}').children('video')[0];
            try{
                stream_{{ $i }} = await navigator.mediaDevices.getUserMedia({
                    video: true, 
                    audio: true
                });

                setSrcObject(stream_{{ $i }}, video_{{ $i }});
                video_{{ $i }}.play();
                video_{{ $i }}.muted = true;
                $('#recorder_outro_{{ $i }}').hide(200);
                $('#counter_outro_{{ $i }}').text(3);
                
                setTimeout(function () {
                    $('#counter_outro_{{ $i }}').text(2);
                }, 1000);
                setTimeout(function () {
                    $('#counter_outro_{{ $i }}').text(1);
                }, 2000);
   
                setTimeout(function(){
                    $('#counter_outro_{{ $i }}').hide();
                    recorder_{{ $i }} = new RecordRTCPromisesHandler(stream_{{ $i }},{
                        type: 'video'
                    });

                    $('.myvideo_outro_{{ $i }}')[0].play();
                    $("#video_recorder_{{ $i }}").show(200);

                    $(btn).addClass('recording');

                    $('#recorder_outro_{{ $i }}').hide(200);
                    $('.streamer_counter_{{ $i }}').show(200);

                    recorder_{{ $i }}.startRecording();
                    $(btn).attr('recording','1');
                
                    duration = (parseInt($('.myvideo_outro_{{ $i }}').attr("duration")))*1000;

                    recoder_duration_{{ $i }} = setTimeout(function(){
                        $("#video_recorder_{{ $i }}").trigger("click");
                    },duration);
                },3000);
            }catch(err){
                alert('There is not device found for recording. Please connect to you camera and refresh you page.');
            }
        }else{
            clearTimeout(recoder_duration_{{ $i }});
            //$(btn)[0].disabled = true;
            $(btn).removeClass('recording');

            await recorder_{{ $i }}.stopRecording();
            let blob = await recorder_{{ $i }}.getBlob();
            url = await upload_blob_video_{{ $i }}(blob);
            
            stream_{{ $i }}.stop();

            $(btn).attr('recording','0');
        }
        
    });    

    function upload_blob_video_{{ $i }}(blob){
        hold_on();
        var vdo = $('.streamer_counter_{{ $i }}');
        var data = new FormData();
        data.append('file', blob);
        $.ajax({
            url: "{{ route('upload_blob_video') }}",
            type:"post",
            data: data,
            contentType: false,
            processData: false,
            success: function(data) {
                hold_off();
               $(vdo).html('<video width="100%" controls><source src="{{ URL('/') }}/'+data+'"></video>');
               $('#video_link_{{ $i }}').val(data);
            }    
        });
    }
</script>
@endfor



@for($i=0;$i<count($template);$i++)
<script type="text/javascript">
var msg_box_outro_{{ $i }}= document.getElementById('msg_box_outro_{{ $i }}'),
    button_outro_{{ $i }} = document.getElementById('recorder_outro_{{ $i }}'),
    canvas_outro_{{ $i }} = document.getElementById('canvas_outro_{{ $i }}'),
    lang_outro_{{ $i }} = {
        'mic_error': 'Microphone not found.', //Ошибка доступа к микрофону
        'press_to_start': 'Press to start recording', //Нажмите для начала записи
        'recording': 'Recording', //Запись
        'play': 'Play', //Воспроизвести
        'stop': 'Stop', //Остановить
        'download': '', //Скачать
        'use_https': 'This application in not working over insecure connection. Try to use HTTPS'
    },
    time;
msg_box_outro_{{ $i }}.innerHTML = lang_outro_{{ $i }}.press_to_start;
if (navigator.mediaDevices === undefined) {
    navigator.mediaDevices = {};
}
if (navigator.mediaDevices.getUserMedia === undefined) {
    navigator.mediaDevices.getUserMedia = function (constrains) {
        var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia
        if (!getUserMedia) {
            return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
        }
        return new Promise(function (resolve, reject) {
            getUserMedia.call(navigator, constrains, resolve, reject);
        });
    }
}
if (navigator.mediaDevices.getUserMedia) {
    var btn_status_outro_{{ $i }} = 'inactive',
        mediaRecorder_outro_{{ $i }},
        chunks_outro_{{ $i }} = [],
        audio_outro_{{ $i }} = new Audio(),
        mediaStream_outro_{{ $i }},
        audioSrc_outro_{{ $i }},
        type_outro_{{ $i }} = {
            'type': 'audio/ogg,codecs=opus'
        },
        ctx_outro_{{ $i }},
        analys_outro_{{ $i }},
        blob_outro_{{ $i }};
    if ($("#recorder_outro_{{ $i }}").length != 0) {
        button_outro_{{ $i }}.onclick = function () {
            if (btn_status_outro_{{ $i }} == 'inactive') {
                play_video_outro_{{ $i }}()
                setTimeout(function () {
                    start_outro_{{ $i }}();
                }, 3000);
            } else if (btn_status_outro_{{ $i }} == 'recording') {
                clear_interval_outro_{{ $i }}();
                button_outro_{{ $i }}.disabled = true;
                stop_outro_{{ $i }}();
            }
        }
    }

    function parseTime_outro_{{ $i }}(sec) {
        var h = parseInt(sec / 3600);
        var m = parseInt(sec / 60);
        var sec = sec - (h * 3600 + m * 60);
        h = h == 0 ? '' : h + ':';
        sec = sec < 10 ? '0' + sec : sec;
        return h + m + ':' + sec;
    }

    function start_outro_{{ $i }}() {
        navigator.mediaDevices.getUserMedia({
            'audio': true
        }).then(function (stream) {
            mediaRecorder_outro_{{ $i }} = new MediaRecorder(stream);
            mediaRecorder_outro_{{ $i }}.start();
            button_outro_{{ $i }}.classList.add('recording');
            btn_status_outro_{{ $i }} = 'recording';
            msg_box_outro_{{ $i }}.innerHTML = lang_outro_{{ $i }}.recording;
            if (navigator.vibrate) navigator.vibrate(150);
            time = Math.ceil(new Date().getTime() / 1000);
            mediaRecorder_outro_{{ $i }}.ondataavailable = function (event) {
                chunks_outro_{{ $i }}.push(event.data);
            }
            mediaRecorder_outro_{{ $i }}.onstop = function () {
                stream.getTracks().forEach(function (track) {
                    track.stop()
                });
                blob_outro_{{ $i }} = new Blob(chunks_outro_{{ $i }}, type);
                audioSrc = window.URL.createObjectURL(blob_outro_{{ $i }});
                
                audio.src = audioSrc;
                upload_blob_outro_{{ $i }}(blob_outro_{{ $i }});
                chunks_outro_{{ $i }} = [];
            }
        }).catch(function (error) {
            console.log(error);
            if (location.protocol != 'https:') {
                msg_box_outro_{{ $i }}.innerHTML = lang_outro_{{ $i }}.mic_error + '<br>' + lang_outro_{{ $i }}.use_https;
            } else {
                msg_box_outro_{{ $i }}.innerHTML = lang_outro_{{ $i }}.mic_error;
            }
            button_outro_{{ $i }}.disabled = true;
        });
    }

    function stop_outro_{{ $i }}() {
        mediaRecorder_outro_{{ $i }}.stop();
        button_outro_{{ $i }}.classList.remove('recording');
        btn_status_outro_{{ $i }} = 'inactive';
        if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
        var now = Math.ceil(new Date().getTime() / 1000);
        <!-- var t = parseTime(now - time); -->
        var t = '';
        msg_box_outro_{{ $i }}.innerHTML = '<a href="#" onclick="play_outro_{{ $i }}(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.play + ' ' + t + '</a><br>' + '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.download + '</a>'
    }

    function play_outro_{{ $i }}() {
        play_video_to_preview_outro_{{ $i }}();
        audio_outro_{{ $i }}.play();
        msg_box.innerHTML = '<a href="#" onclick="pause_outro_{{ $i }}(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.stop + '</a><br>' + '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.download + '</a>';
    }

    function play_video_to_preview_outro_{{ $i }}(){
        $('.myvideo_outro_{{ $i }}').get(0).play();
    }

    function pause_outro_{{ $i }}() {
        audio_outro_{{ $i }}.pause();
        audio_outro_{{ $i }}.currentTime = 0;
        msg_box_outro_{{ $i }}.innerHTML = '<a href="#" onclick="play_outro_{{ $i }}(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.play + '</a><br>' + '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.download + '</a>'
    }

    function roundedRect_outro_{{ $i }}(ctx, x, y, width, height, radius, fill) {
        ctx.beginPath();
        ctx.moveTo(x, y + radius);
        ctx.lineTo(x, y + height - radius);
        ctx.quadraticCurveTo(x, y + height, x + radius, y + height);
        ctx.lineTo(x + width - radius, y + height);
        ctx.quadraticCurveTo(x + width, y + height, x + width, y + height - radius);
        ctx.lineTo(x + width, y + radius);
        ctx.quadraticCurveTo(x + width, y, x + width - radius, y);
        ctx.lineTo(x + radius, y);
        ctx.quadraticCurveTo(x, y, x, y + radius);
        ctx.fillStyle = fill;
        ctx.fill();
    }

    function save_outro_{{ $i }}() {
        var a = document.createElement('a');
        a.download = 'record.ogg';
        a.href = audioSrc_outro_{{ $i }};
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
} else {
    if (location.protocol != 'https:') {
        msg_box_outro_{{ $i }}.innerHTML = lang_outro_{{ $i }}.mic_error + '<br>' + lang_outro_{{ $i }}.use_https;
    } else {
        msg_box_outro_{{ $i }}.innerHTML = lang_outro_{{ $i }}.mic_error;
    }
    if ($("#recorder_outro_{{ $i }}").length != 0) {
        button_outro_{{ $i }}.disabled = true;
    }
}

function play_video_outro_{{ $i }}() {
    duration = (parseInt($('.myvideo_outro_{{ $i }}').attr("duration"))+4)*1000;
    $("#video_recorder_{{ $i }}").hide(200);
    setTimeout(function(){ 
        button_outro_{{ $i }}.click();
    },duration);

    $('#counter_outro_{{ $i }}').text(3);
    setTimeout(function () {
        $('#counter_outro_{{ $i }}').text(2);
    }, 1000);
    setTimeout(function () {
        $('#counter_outro_{{ $i }}').text(1);
    }, 2000);
    setTimeout(function () {
        $('#counter_outro_{{ $i }}').hide();
        $('.myvideo_outro_{{ $i }}').get(0).play();
        
        start_counter_outro_{{ $i }}();
    }, 3000);
}

function start_counter_outro_{{ $i }}() {
    if ($('#counter_box_outro_{{ $i }}').text()) {
        j_{{ $i }} = 0;
        $('#counter_box_outro_{{ $i }}').text(j_{{ $i }})
        $('#counter_box_outro_{{ $i }}').show();
    } else {
        j_{{ $i }} = 0;
        // $('#counter_box').show();
        $('#counter_box_outro_{{ $i }}').text(j_{{ $i }}++);
        setInterval(function () {
            $('#counter_box_outro_{{ $i }}').text(j_{{ $i }}++)
        }, 1000);
    }
}

function retake_{{ $i }}() {
    if (btn_status_outro_{{ $i }} == 'recording') {
        clear_interval_outro_{{ $i }}();
        stop_outro_{{ $i }}();
    }
    msg_box_outro_{{ $i }}.innerHTML = '';
    $('#outro_{{ $i }}_audio').val('');
    $('#counter_outro_{{ $i }}').show();
    $('#counter_outro_{{ $i }}').text('');
    $('#recorder_outro_{{ $i }}').removeAttr('disabled');

    $("#video_link_{{ $i }}").val('');
    $(".streamer_counter_{{ $i }}").html('<video width="100%"></video>');
    $(".streamer_counter_{{ $i }}").hide(200);

    if($("#video_recorder_{{ $i }}").attr('recording')==1){
        $("#video_recorder_{{ $i }}").trigger("click");
    }
    $("#video_recorder_{{ $i }}").show(200);
    $("#recorder_outro_{{ $i }}").show(200);

}

function clear_interval_outro_{{ $i }}() {
    $('#counter_box_outro_{{ $i }}').hide();
}

function upload_blob_outro_{{ $i }}(blob) {
    var data = new FormData();
    data.append('file', blob);
    hold_on();
    $.ajax({
        url: "{{ route('upload_blob') }}",
        type: "post",
        data: data,
        contentType: false,
        processData: false,
        success: function (data) {
            $('#outro_{{ $i }}_audio').val(data);
            hold_off();
        }
    });
}
</script>
@endfor
@endsection