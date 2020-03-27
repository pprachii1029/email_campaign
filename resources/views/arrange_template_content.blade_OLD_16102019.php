@extends('layouts.app') 

@section('content')
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<div class="recorder d-none" onclick="">
    <button type="button" class="record_btn" id="recorder"><i class="fa fa-microphone"></i></button>
    <p id="msg_box"></p>
    <p id="counter"></p>
</div>
<style>
    .draggable{
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
    }
</style>
<section id="content">
    <div class="container-fluid title-bar">
        <div class="row">
            <div class="col-md-4 mt-2">
                <h3 class="page-title pl-3">Arrange Template</h3>
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('make_video') }}" onsubmit="hold_on()">
        <div class="container-fluid mt-4 droppable">
            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                <ol class="carousel-indicators">
                    @foreach($template as $key => $row)
                    <li data-target="#myCarousel" data-slide-to="{{ $key }}" class="{{ $key==0 ? 'active' : ''}}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach($template as $key => $row)
                    <div class="item {{ $key==0 ? 'active' : ''}}">
                        <div class="video_div">
                            <input type="hidden" value="{{ $row['video'] }}" name="video[]">
                            <input type="hidden" value="{{ $row['id'] }}" name="id[]">
                            @if($row['content']=='video')
                                <video width="100%" controls class="myvideo_outro_{{ $key }}" style="height:100%">
                                    <source src="{{ URL($row['video']) }}" >
                                </video>
                            @else
                                <video width="100%" controls class="myvideo_outro_{{ $key }}" style="height:100%">
                                    <source src="{{ URL($row['video']) }}" >
                                </video>
                            @endif
                        </div>
                        <div class="audio_div text-center  {{ $row['mute']==0 ? 'd-none' : '' }}">
                            <div class="col-md-12 text-center counter">
                                <span id="counter_outro_{{ $key }}" style="font-size: 40px;text-align: center;display: block;"></span>
                            </div>
                            <div class="recorder mt-5">
                                <input type="hidden" value="" id="outro_{{ $key }}_audio" name="audio[]">
                                <div class="recorder_wrapper">
                                    <div class="recorder_outro_{{ $key }}" onclick="">
                                        <button type="button" class="record_btn" id="recorder_outro_{{ $key }}"><i class="fa fa-microphone"></i></button>
                                        <p id="msg_box_outro_{{ $key }}"></p>
                                        <p id="counter_box_outro_{{ $key }}"></p>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="retake_outro_{{ $key }}()">Retake</button>
                        </div>
                    </div>
                    <div class="extra-div">

                    </div>
                    @endforeach
                </div>

                <a class="left carousel-control" href="#myCarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

            <div class="clearfix"></div>
            <br>

            <div class="container-fluid mt-4 col-md-12">
                <div class="row"  id="video_view">
                    <div class="col-md-12 pt-4 pl-5 pb-5">
                        <input type="hidden" name="template_id" id="template_id" value="{{ $template_id }}">
                        <div class="row d-flex justify-content-center mt-5">
                            <button type="submit" name="submit" value="preview" class="btn preview-btn ml-5">Preview</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </form>
</section>
<!-- <script src="{{ URL('public/assets/js/record.js') }}" id="rec" count="{{ count($template) }}"></script> -->
<!-- <script>
    $(document).ready(function() {
        $("#video_view").sortable({
            update: function(event, ui) {
                var changedList = this.id;
                var order   = $(this).sortable('toArray');
                var new_pos = order.join(',');
                $('#new_pos').val(new_pos);
            }
        });
        $( ".draggable" ).disableSelection();            
    });
</script> -->
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
        msg_box_outro_{{ $i }}.innerHTML = '<a href="#" onclick="play(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.play + ' ' + t + '</a><br>' + '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.download + '</a>'
    }

    function play_outro_{{ $i }}() {
        play_video_to_preview_outro_{{ $i }}();
        audio_outro_{{ $i }}.play();
        msg_box.innerHTML = '<a href="#" onclick="pause(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.stop + '</a><br>' + '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.download + '</a>';
    }

    function pause_outro_{{ $i }}() {
        audio_outro_{{ $i }}.pause();
        audio_outro_{{ $i }}.currentTime = 0;
        msg_box_outro_{{ $i }}.innerHTML = '<a href="#" onclick="play(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.play + '</a><br>' + '<a href="#" onclick="save(); return false;" class="txt_btn">' + lang_outro_{{ $i }}.download + '</a>'
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

function retake_outro_{{ $i }}() {
    if (btn_status_outro_{{ $i }} == 'recording') {
        clear_interval_outro_{{ $i }}();
        stop_outro_{{ $i }}();
    }
    msg_box_outro_{{ $i }}.innerHTML = '';
    $('#outro_{{ $i }}_audio').val('');
    $('#counter_outro_{{ $i }}').show();
    $('#counter_outro_{{ $i }}').text('');
    $('#recorder_outro_{{ $i }}').removeAttr('disabled');
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