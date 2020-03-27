@extends('layouts.app')
@section('content')
    <style>
        .draggable{
            height: 150px !important;
        }
        .draggable img{
            height: 150px !important;
        }
        .list-group{
            cursor: pointer;
        }
        .recorder{
            width: 270px !important;
        }
        .add_outro_pic,.add_outro_pic1{
            border: 1px solid gray;
            border-radius: 7px;
            color: gray;
            height: 100px;
            width: 100px;
            cursor: pointer;
        }
        .add_outro_pic1{
            margin: 0px 6px;    
        }
        .plus-btn{
            border: 1px solid #333;
            width: max-content;
            padding: 10px 15px;
            border-radius: 10px;
        }
    </style>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
    <script src="https://editor.unlayer.com/embed.js"></script>

    <section id="content">
        <div class="container-fluid title-bar">
            <div class="row">
                <div class="col-md-4 mt-2">
                    <h3 class="page-title pl-3">Add Template</h3>
                </div>
                <div class="col-md-8 d-flex justify-content-end"></div>
            </div>
        </div>
        <div class="container-fluid main-side mt-4 ">
            <div class="row ">
                <div class="col-md-12 pt-4 pl-5 pb-5">
                   <!--  {{ route('save_template') }} -->
                    <form class="" method="POST" action="{{ route('save_template') }}" enctype="multipart/form-data" onsubmit="changeListorder();hold_on()">
                        <input type="hidden" name="unlink" id="unlink" >
                        <h3> Enter Template Details</h3>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Template Title</label>
                        </div>
                        
                        <div class="col-sm-6">
                            <input type="text" name="title" class="form-control new-text temp_title" id="inputPassword" value="" required>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Template Description</label>
                        </div>
                       
                        <!-- <div id="editor"> -->
                        <div class="col-sm-6">
                            <textarea class="form-control new-text temp_desc" id="exampleFormControlTextarea1" rows="3" name="description" required></textarea>
                        </div>


                        <h4 class="text-left py-3">Template Intro</h4>
                        <div class=" row template-right-side">
                            <input type="radio" name="intro" value="contact" checked class="intro">&nbsp;&nbsp;Contact Audio&emsp;&emsp;
                            <input type="radio" name="intro" value="custom" class="intro">&nbsp;&nbsp;Custom Audio
                        </div>
                        <br>
                        <div class="col-md-5 text-center counter">
                            <span id="counter" style="font-size: 40px;text-align: center;display: block;"></span>
                        </div>
                        <div class="row template-right-side" id="into_audio_div" style="display: none;">
                            <div class="recorder mt-5">
                                <input type="hidden" value="" id="intro_audio" name="intro_audio">
                                <div class="recorder_wrapper">
                                    <div class="recorder" onclick="">
                                        <button type="button" class="record_btn" id="recorder"><i class="fa fa-microphone"></i></button>
                                        <p id="msg_box"></p>
                                        <p id="counter_box"></p>
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="retake()">Retake</button>
                                </div>
                            </div>
                        </div>
                        <br>
                       <!--  <button type="button" class="btn btn-primary" onclick="changeListorder()">Done order</button> -->
                        <h4 class="text-left py-3">Template Elements</h4>
                        <!-- <div class=" row template-right-side">
                            <ul class="template-elements-ul" style=" display: flex;">
                                <li onclick="append_snapshot()">
                                    <img src="{{ URL('public/assets/img/snapshot.png') }}">Snapshot
                                </li>
                                <li onclick="append_video()">
                                    <img src="{{ URL('public/assets/img/video.png') }}">Video
                                </li>
                                <li onclick="append_url()">
                                    <img src="{{ URL('public/assets/img/url.png') }}">URL
                                </li>
                                <li onclick="append_photo()">
                                    <img src="{{ URL('public/assets/img/photo.png') }}">Photo
                                </li>
                            </ul>
                        </div> -->
                        <table class="table tab1 table-responsive">
                            <!-- <thead>
                                <tr>
                                    <th scope="col" style="width:10%">Order</th>
                                    <th scope="col" style="width:20%">Add</th>
                                    <th scope="col" style="width:25%">Preview</th>
                                    <th scope="col" style="width:25%">Set Duration</th>
                                    <th scope="col" style="width:20%">Action</th>
                                </tr>
                            </thead> -->

                            <div id="simple-list">
                                <tbody id="example1">
                                  <!--   <tbody id="append"> -->
                                      
                                        
                                    <!-- <div style="padding: 0" class="col-12">
                                        <pre class="prettyprint">new Sortable(example1, {
                                            animation: 150,
                                            ghostClass: 'blue-background-class'
                                        });</pre>
                                    </div> -->
                                <!-- </tbody> -->
                                </tbody>
                            </div>

                         <!--    <div id="example1" class="list-group col">
                                <tbody id="append">
                                    
                                    <div style="padding: 0" class="col-12">
                                        <pre class="prettyprint">new Sortable(example1, {
                                            animation: 150,
                                            ghostClass: 'blue-background-class'
                                        });</pre>
                                    </div>
                                </tbody>
                            </div> -->

                        </table>
                        <div class="col-md-12 text-center">
                            <span onclick="append()" class="plus-btn">
                                <i class="fa fa-plus fa-2x"></i>
                            </span> 
                        </div>
                        <br>
                        <div class="row d-flex justify-content-center mt-5">
                            <a href="">
                            <!--     <button  id="clikcbtn" class="btn preview-btn">Get Html</button> -->
                                <button type="submit" class="btn preview-btn">Arrange</button>
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>


<!-- <script src="{{ URL('public/assets/js/custom2.js') }}"></script> -->
    <script type="text/javascript">
        unlayer.init({
          id: 'editor',
          projectId: 1234,
          displayMode: 'email'
        });
    
        $('#clikcbtn').click(function(){
            unlayer.exportHtml(function(data) {
              var json = data.design; // design json
              var html = data.html; // final html
              console.log(html);
            })
        });
        function changeListorder(){
            var i = 1;
            $('#example1 > tr').each(function() {
               $(this).find('th input').val(i);
                i++;
                // var text = $(this).find('td').eq(0).text();
                // console.log(value);
                // console.log(text);
            });
            
        }


        $(document).ready(function(){
            $('.temp_title1').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==35){
                    $.dialog({
                        title: 'Select input tag',
                        animation: 'RotateX',
                        keyboardEnabled: true,
                        content:'<ul class="list-group"><li class="list-group-item" onclick="add_tag(\'firstName\');">First Name</li><li class="list-group-item" onclick="add_tag(\'lastName\')">Last Name</li><li class="list-group-item" onclick="add_tag(\'email\')">Email</li><li class="list-group-item" onclick="add_tag(\'designation\')">Designation</li><li class="list-group-item" onclick="add_tag(\'phoneNumber\')">Phone Number</li><li class="list-group-item" onclick="add_tag(\'website\')">Website</li><li class="list-group-item" onclick="add_tag(\'facebook\')">Facebook</li><li class="list-group-item" onclick="add_tag(\'linkedin\')">Linkedin</li></ul>',
                    });
                }
            });

            $('.temp_desc1').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==35){
                    $.dialog({
                        title: 'Select input tag',
                        animation: 'RotateX',
                        keyboardEnabled: true,
                        content:'<ul class="list-group"><li class="list-group-item" onclick="add_tag_desc(\'firstName\');">First Name</li><li class="list-group-item" onclick="add_tag_desc(\'lastName\')">Last Name</li><li class="list-group-item" onclick="add_tag_desc(\'email\')">Email</li><li class="list-group-item" onclick="add_tag_desc(\'designation\')">Designation</li><li class="list-group-item" onclick="add_tag_desc(\'phoneNumber\')">Phone Number</li><li class="list-group-item" onclick="add_tag_desc(\'website\')">Website</li><li class="list-group-item" onclick="add_tag_desc(\'facebook\')">Facebook</li><li class="list-group-item" onclick="add_tag_desc(\'linkedin\')">Linkedin</li></ul>',
                    });
                }
            });  

            $('.add_outro_pic').click(function(){
                val = $('.add_outro_pic').before('<div class="upload-btn-wrapper1"><span class="add_outro_pic1"><i class="fa fa-cloud fa-3x" style="margin: auto;"></i></span><input type="file" name="outro_picture[]"  onchange="filePreview1(this)" /></div>');
            });

            $(".intro").change(function(){
                val = $(this).val();
                if(val=='contact'){
                    $("#into_audio_div").hide(200);
                }else{
                    $("#into_audio_div").show(200);
                }
            });       
        });

        function append(){
            html = '<ul class="template-elements-ul" style=" display: flex;"><li onclick="append_snapshot()"> <img src="{{ URL('public/assets/img/snapshot.png') }}">Snapshot</li><li onclick="append_video()"> <img src="{{ URL('public/assets/img/video.png') }}">Video</li><li onclick="append_url()"> <img src="{{ URL('public/assets/img/url.png') }}">URL</li><li onclick="append_photo()"> <img src="{{ URL('public/assets/img/photo.png') }}">Photo</li></ul>';
            $.dialog({
                title:'Select',
                content: html,
                columnClass:'col-md-8 col-md-2-offset'
            })
        }

        function change_order(curr){
            matchcount = 0
            val = parseInt($(curr).val());
            
            if($.isNumeric(val)){
                $(".order").each(function(){
                    if($(this).val()==val){
                        matchcount++;
                    }
                });
                if(matchcount>1){
                    $(curr).val(val+1);
                    change_order(curr);
                }
            }else{
                $(curr).val('');
            }
        }

        function filePreview1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(input).prev().html("<img src='"+e.target.result+"' width='100%'>");
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function play_video(){
            $('#counter').text(3);
            setTimeout(function(){ $('#counter').text(2); },1000);
            setTimeout(function(){ $('#counter').text(1); },2000);

            setTimeout(function(){ 
                $('#counter').hide();
                start_counter();
            },3000);
        }

        function start_counter(){
            if($('#counter_box').text()){
                i = 0;
                $('#counter_box').text(i)
                $('#counter_box').show();
            }else{
                i = 0;
                // $('#counter_box').show();
                $('#counter_box').text(i++);
                setInterval(function(){ $('#counter_box').text(i++) },1000);
            }
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
                    $('#intro_audio').val(data);
                    hold_off();
                }    
            });
        }

        function clear_interval(){
            $('#counter_box').hide();
        }

        function add_tag(val){
            str = $('.temp_title').val();
            $('.temp_title').val(str+val);
            $('.jconfirm-closeIcon').trigger("click");
        }

        function add_tag_desc(val){
            str = $('.temp_desc').val();
            $('.temp_desc').val(str+val);
            $('.jconfirm-closeIcon').trigger("click");
        }
    </script>
    <script>
        var vdo = 0;
        var urldx = 0;
        var photo = 0;
        var snapshot = 0;
        var order = 1;
        unlink_arr= [];
        function append_video(){
            idx = vdo++;
            $.ajax({
                url:'{{ route("append_video") }}',
                type:'post',
                data:{'index':idx,'order':order++},
                success: function (data){
                    $('#example1').append(data);
                    // $('#video').show(200);
                    $(".jconfirm-closeIcon").trigger("click");
                }
            })
        }
        
        function append_url(){
            idx = urldx++;
            $.ajax({
                url:'{{ route("append_url") }}',
                type:'post',
                data:{'index':idx,'order':order++},
                success: function (data){
                    $('#example1').append(data);
                    // $('#url').show(200);
                    $(".jconfirm-closeIcon").trigger("click");
                }
            })
        }

        function append_photo(){
            idx = photo++;
            $.ajax({
                url:'{{ route("append_photo") }}',
                type:'post',
                data:{'index':idx,'order':order++},
                success: function (data){
                    $('#example1').append(data);
                    // $('#photo').show(200);
                    $(".jconfirm-closeIcon").trigger("click");
                }
            });
        }

        function append_snapshot(){
            idx = snapshot++;
            $.ajax({
                url:'{{ route("append_snapshot") }}',
                type:'post',
                data:{'index':idx,'order':order++},
                success: function (data){
                    $('#example1').append(data);
                    $(".jconfirm-closeIcon").trigger("click");
                }
            });
        }

        function picture_preview(input,id){
            var file = input.files[0];
            var fileType = file["type"];
            var validImageTypes = ["image/gif", "image/jpeg", "image/png", "image/jpg", "image/svg", "image/svg+xml"];
            if ($.inArray(fileType, validImageTypes) > -1) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#'+id).html('<img src="'+e.target.result+'" class="img-fluid"/>');
                }
                reader.readAsDataURL(input.files[0]);
            }else{
                alert("Please select a image file. Eg: (.gif .jpeg .png)");
                $(input).val('');
                $('#'+id).html('');
            }
        }

        function filePreview(input,id) {
            $('#'+id).html('<i class="fa fa-spin fa-spinner fa-3x">');
            var file_data = $(input).prop("files")[0];
            var form_data = new FormData(); 
            form_data.append("file", file_data);
            form_data.append("content", $(input).attr('content'));
            $.ajax({
                url: "{{ route('upload_files') }}",
                dataType: 'script',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(data){
                    data = JSON.parse(data);
                    $('#'+id).html(data.html);
                    unlink_arr.push(data.url);
                    $('#unlink').val(JSON.stringify(unlink_arr));
                }
            });
        }

        function video_preview(input,id){
            var file = input.files[0];
            var fileType = file["type"];
            var validImageTypes = ["video/mp4", "video/avi", "video/flv", "video/mov", "video/quicktime"];
            if ($.inArray(fileType, validImageTypes) > -1) {
                file = input.files[0];
                url  = URL.createObjectURL(file);
                html = '<video width="100%" controls class="myvideo"><source src="'+url+'" ></video>';
                $('#'+id).html(html);
                duration = $('#'+id).children('video')[0].duration;
                setTimeout(function(){
                    console.log($('#'+id).children('video')[0].duration);
                },5000);
            }else{
                alert("Please select a video file. Eg: (.mp4 .avi .flv)");
                $(input).val('');
                $('#'+id).html('');
            }
        }

        function capture_url(input,id,base64datahere){
            $('#'+id).html('<i class="fa fa-spin fa-spinner fa-3x">');
            hold_on();
            url = $('#'+input).val();
            $.ajax({
                url: '{{ route("capture_url") }}',
                type:'post',
                data:{'url':url},
                success:function(data){
                    $('#'+id).attr('src','data:image/jpeg;base64,'+data);
                    $('#'+base64datahere).val(data);
                    hold_off();
                },
                error: function(data){
                    alert('Url is not valid.');
                    hold_off();
                }
            });
        }

        function view_snapshot(val,ele){
            //alert(val);
            img     = $(ele).parent('td').next().children('img');
            input   = $(ele).parent('td').next().children('input');

            if(val=='Facebook'){
                $(img).attr('src','data:image/jpeg;base64,'+facebook);
                $(input).val(facebook);
            }else if(val=='Website'){
                $(img).attr('src','data:image/jpeg;base64,'+website);
                $(input).val(website);
            }else if(val=="Linked In"){
                $(img).attr('src','data:image/jpeg;base64,'+linkedin);
                $(input).val(linkedin);
            }else{
                $(img).attr('src','');
                $(input).val('');
            }
        }

        function mute_unmute(ele){
            if($(ele).children('input').val()==1){
                $(ele).children('input').val('0');
                $(ele).children('img').attr('src',"{{ URL('public/assets/img/unmute.png') }}");
            }else{
                $(ele).children('input').val('1');
                $(ele).children('img').attr('src',"{{ URL('public/assets/img/mute.png') }}");
                $(ele).children('img').attr('width',"30px");
            }
        }

        function delete_row(ele){
            $(ele).parent('td').parent('tr').remove();
        }
    </script>
@endsection