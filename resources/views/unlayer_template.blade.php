
@extends('layouts.app')
@section('content')
    <?php header("Access-Control-Allow-Origin: *");?>
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
        <div class="container-fluid main-side mt-4 editor_section ">
        	<div class="field_Templatebtn">
        		<div class="row fieldsCont">
        			<div class="col-md-4 mt-2">
	                    <h3 class="page-title pl-3">Template Name</h3>
	                </div>
	                <div class="col-md-8 d-flex ">
	                	<input type="text" name="name" class="template_name" required>
	                </div>
        		</div>
        	</div>
        </div>
        <div class="clearfix"></div>
        <div class="container-fluid main-side mt-4 editor_section ">
            <div class="row editonContainer">
            	<div id="editor"> </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="container-fluid main-side mt-4 editor_submitBtn ">
            <div class="row justify-content-center">
            	<div class=" d-flex mt-3 mb-3">
                    <button  id="clikcbtn" class="btn preview-btn">Save it</button>
                </div>
            </div>

        </div>
        
        <button type="button" class="btn btn-info btn-lg hide buttonOf_model" data-toggle="modal" data-target="#editor_view">Open Modal</button>

        <!-- Modal -->
        <div class="modal fade editor_view" id="editor_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
            <form id="regForm" action="">
                        <!-- One "tab" for each step in the form: -->
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Email template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default saveMy_Template" data-dismiss="modal">Save it</button>
              </div>
            </form> 
            </div>
          </div>
        </div>
        <!-- end -->

    </section>
<style>
	.editor_section .row #editor {
	    margin-left: -30px;
	    height: 500px;
	}
	.field_Templatebtn .fieldsCont {
    padding: 10px;
}
.editor_submitBtn {
    margin-bottom: 20px;
}

</style>


<!-- <script src=""></script> -->
    <script type="text/javascript">
        unlayer.init({
          id            : 'editor',
          projectId     : 3956,
          displayMode   : 'email',
          customCSS     : '{{ URL("public/assets/js/custom2.css") }}',
          appearance: {
            theme: 'dark',
          }
        });
        var finalHtml = '';
        $(document).on('click','#clikcbtn',function(){
            unlayer.exportHtml(function(data) {
              var json = data.design; // design json
              var html = data.html; // final html
              var name = jQuery('input.template_name').val();
              if ( name == '') {
                alert('Please enter the template name.');
                return false;
              }
              jQuery('div#editor_view .modal-body').html(html);
              finalHtml = html;
              jQuery('.buttonOf_model').trigger('click');
              
              
              //save_email_html(html,name);
              
            })
        });
        jQuery(document).on('click','.saveMy_Template',function(){
            var name = jQuery('input.template_name').val();
            //console.log(jQuery('div#editor_view .modal-body table.nl-container').html() );
            var dataURL = {};
            html2canvas(document.querySelector("table.nl-container"),{allowTaint : false, useCORS: true}).then(canvas => {
              // html2canvas(document.querySelector("table.nl-container")).then(canvas => {

                dataURL     = canvas.toDataURL();  
                console.log(dataURL); 
                save_email_html(finalHtml,name,dataURL);  
              }); 

            // html2canvas(document.querySelector("table.nl-container"), {
            //   //useCORS: true,
            //   onrendered: function(canvas) {
            //     dataURL     = canvas.toDataURL('image/png');  
            //                 console.log(dataURL); 
            //                 save_email_html(finalHtml,name,dataURL);
            //   }
            // });     
        });

        function save_email_html(html,name,dataURL){
        	$.confirm({
            title: 'Confirm',
            content:'Have you completed your template?',
            buttons:{
                yes:{
                    text:'Yes',
                    action:function(){
                    	hold_on();
                        $.ajax({
			                url: '{{ route("save_email_html") }}',
			                type:'post',
			                data:{'html':html, 'name': name,'dataURL':dataURL},
			                success:function(data){
			                    hold_off();
			                    window.location.replace('{{ route("email_template_list") }}');
			                },
			                error: function(data){
			                    alert('something went wrong.');
			                    hold_off();
			                }
			            });
                    }
                },
                no:{
                    text:'No'
                }
            }
        });
            
            
        }
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
