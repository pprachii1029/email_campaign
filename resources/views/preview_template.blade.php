@extends('layouts.app')

@section('content')
    <style type="text/css">
        .loader{
            text-align: center;
            font-size: 30px;
            color: green;
        }
    </style>
    <section id="content">
        <div class="container-fluid title-bar">
            <div class="row">
                <div class="col-md-4 mt-2">
                    <h3 class="page-title pl-3">Preview Template</h3>
                </div>
                <div class="col-md-8 d-flex justify-content-end"></div>
            </div>
        </div>
        <div class="container-fluid main-side mt-4 mb-5 pb-5 ">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="ml-5 mt-4">{{ $title }}</h3>
                    <div class="text-center mt-5 ">
                        <form method="POST" action="{{ route('final_video') }}" onsubmit="hold_on()">
                            <div class="row">
                                <div class="col-md-6 offset-md-3">
                                    <video width="100%" controls class="myvideo" style="height:400px">
                                        <source src="{{ URL($final_video) }}" >
                                    </video>

                                    <p class="mt-3">&emsp;</p>
                                    <div class="slidecontainer name-clip-slider mt-3" style="display:none">
                                        <audio controls width="100%">
                                            <source src="" type="audio/mp3" id="audio_src">
                                        </audio>
                                    </div>
                                    
                                    <div class="col-md-12 text-center counter">
                                        <span id="counter" style="font-size: 40px;text-align: center;display: block;"></span>
                                    </div>

                                    <!-- <div class="recorder mt-5">
                                        <input type="hidden" value="{{ $id }}" name="template_id">
                                        <input type="hidden" value="" id="recorded_file" name="recorded_file">
                                        <input type="hidden" value="{{ $final_video }}" id="final_video" name="final_video">
                                        <div class="recorder_wrapper">
                                            <div class="recorder" onclick="">
                                                <button type="button" class="record_btn" id="recorder"><i class="fa fa-microphone"></i></button>
                                                <p id="msg_box"></p>
                                                <p id="counter_box"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row d-flex justify-content-center mt-5">
                                        <a href="#">
                                            <button type="button" class="btn preview-btn ml-5" onclick="preview()">Preview</button>
                                        </a>
                                    </div> -->

                                    <div class="row d-flex justify-content-center mt-5">
                                        <a href="#">
                                            <button type="submit" class="btn preview-btn">SAVE</button>
                                        </a>
                                       <!--  <a href="#">
                                            <button type="button" class="btn preview-btn ml-5" onclick="retake()">RETAKE</button>
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>    

    <script>
        function play_video(){
            $('#counter').text(3);
            setTimeout(function(){ $('#counter').text(2); },1000);
            setTimeout(function(){ $('#counter').text(1); },2000);

            setTimeout(function(){ 
                $('#counter').hide();
                $('.myvideo').get(0).play(); 
                start_counter();
            },3000);
        }

        function preview(){
            unqno = Math.random().toString(36).substr(2, 9);
            audio = $('#recorded_file').val();
            video = $('#final_video').val();
            $.alert({
                title:'Preview',
                columnClass: 'col-md-6 col-md-offset-3',
                content:'<div class="loader"><i class="fa fa-spin fa-2x fa-cog"></i></div>',
                onContentReady: function(){
                    pop = this;
                    $.ajax({
                        url: '{{ route("preview_with_audio") }}',
                        type:'post',
                        data:{'audio':audio,'video':video},
                        success:function(data){
                            html = '<video width="100%" controls class="myvideo" style="height:400px"><source src="{{ URL("/") }}/'+data+'" ></video>';
                            pop.setContent(html);
                        }
                    });
                }
            });
        }

        function play_video_to_preview(){
            $('.myvideo').get(0).play();
        }

        function start_counter(){
            if($('#counter_box').text()){
                i = 0;
                $('#counter_box').show();
            }else{
                i = 0;
                // $('#counter_box').show();
                $('#counter_box').text(i++);
                setInterval(function(){ $('#counter_box').text(i++) },1000);
            }
        }

        function clear_interval(){
            $('#counter_box').hide();
        }

        function retake(){
            if ( btn_status == 'recording' ){
                clear_interval();
                stop();  
            }   
            msg_box.innerHTML = '';
            $('#recorded_file').val('');
            $('#counter').show();
            $('#counter').text('');
            $('#recorder').removeAttr('disabled');
        }

        function upload_blob(blob){
            var data = new FormData();
            data.append('file', blob);
            hold_on();
            $.ajax({
                url: "{{ route('upload_blob') }}",
                type:"post",
                data: data,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#recorded_file').val(data);
                    $('#audio_src').parent('div').show();
                    // $('#audio_src').attr('src',data);
                    hold_off();
                }    
            });
        }
    </script>
@endsection