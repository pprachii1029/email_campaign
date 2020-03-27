@extends('layouts/app')

@section('content')
<style>
    .xyz{
        border-radius: 5px !important;
        border: 1px solid gray !important;
        background-color: white;
    }
    .rcrdbtn{
        background: transparent;
        border: transparent;
        padding: 20px 20px;
    }
</style>
<section id="content">
	<div class="container-fluid title-bar">
		<div class="row">
			<div class="col-md-2 mt-2">
				<h3 class="page-title pl-3">Add Contacts</h3>
			</div>
			<div class="col-md-5 d-flex justify-content-end">
				<ul class="nav nav-pills mb-3 pl-4" id="pills-tab" role="tablist">
					<li class="nav-item"> <a class="nav-link my-nav-tabs active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true" onclick="$('#upload_csv_btn').hide()">Add Contacts</a>
					</li>
					<li class="nav-item"> <a class="nav-link my-nav-tabs2" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" onclick="$('#upload_csv_btn').show()">Upload Contacts</a>
					</li>
				</ul>
			</div>
			<div class="col-md-2"></div>
			<div class="col-md-3">
				<div class="upload-btn-wrapper1">
					<button class="btn-upload2" id="upload_csv_btn" style="display: none;">Upload CSV
						<img src="{{ URL('public/assets/img/upload-img.png') }}" width="22px">
					</button>
					<input type="file" name="myfile" onchange="upload_csv(this)"/>
                </div>
			</div>
		</div>
	</div>
	<div class="col-md-3 d-flex justify-content-end"></div>
	<div class="container-fluid mt-4 ">
		<div class="row main-side pt-4 pb-5">
			<div class="col-md-12">
				<div class="tab-content" id="pills-tabContent">
					<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <form action="{{ (@$contact) ? route('update_contact') : route('add_contact') }}" method="POST" enctype="multipart/form-data" class="parsley">
                            <input type="hidden" value="{{ (@$contact) ? Crypt::encrypt(@$contact->id) : '' }}" name="id">
                            
                            <div class="form-group row">
								<label for="staticEmail" class="col-sm-2 col-form-label">&emsp;</label>
								<div class="col-sm-6">
                                    <img src="{{ (@$contact->picture) ? URL(@$contact->picture) : '' }}" id="pp_preview" width="150px">
								</div>
                            </div>
                            
							<div class="form-group row">
								<label for="staticEmail" class="col-sm-2 col-form-label">Add Image</label>
								<div class="col-sm-6">
									<div class="upload-btn-wrapper1">
										<button class="btn-upload1">Upload
											<img src="{{ URL('public/assets/img/upload-img.png') }}">
										</button>
										<input type="file" name="picture"  onchange="filePreview(this,'pp_preview')" />
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">First Name</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="inputPassword" placeholder="First Name" name="first_name" value="{{ (@$contact) ? @$contact->first_name : '' }}" required>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Last Name</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="inputPassword" placeholder="Last Name" name="last_name" value="{{ (@$contact) ? @$contact->last_name : '' }}">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Email</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="inputPassword" placeholder="Email" name="email" value="{{ (@$contact) ? @$contact->email : '' }}" required data-parsley-type="email">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Designation</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="inputPassword" placeholder="Designation" name="designation" value="{{ (@$contact) ? @$contact->designation : '' }}">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Phone Number</label>
								<div class="col-sm-6">
									<input type="text" class="form-control" id="inputPassword" placeholder="Phone Number" name="phone_number" value="{{ (@$contact) ? @$contact->phone_number : '' }}">
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Website</label>
								<div class="col-sm-6">
									<input type="text" class="form-control xyz" id="website_input" placeholder="Website" name="website" value="{{ (@$contact) ? @$contact->website : '' }}">
                                </div><i class="fa fa-plus-circle" onclick="capture_url(this,'website_input','website_preview','website_ss')"></i>
							</div>
							<div class="form-group row">
								<label class="col-sm-2 col-form-label">Website Preivew</label>
                                <img src="{{ (@$contact) ? @$contact->website_ss : '' }}" class="pl-3" id="website_preview" width="200px">
                                <input type="hidden" name="website_ss" value="{{ (@$contact) ? @$contact->website_ss : '' }}" id="website_ss"> 
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Facebook URL</label>
								<div class="col-sm-6">
									<input type="text" class="form-control xyz"  id="facebook_input" placeholder="Facebook URL" name="facebook" value="{{ (@$contact) ? @$contact->facebook : '' }}">
								</div><i class="fa fa-plus-circle" onclick="capture_url(this,'facebook_input','facebook_preview','facebook_ss')"></i>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Facebook Preivew</label>
								<div class="col-sm-6">
                                    <img src="{{ (@$contact) ? @$contact->facebook_ss : '' }}" class="pl-3" id="facebook_preview" width="200px">
                                    <input type="hidden" name="facebook_ss" value="{{ (@$contact) ? @$contact->facebook_ss : '' }}" id="facebook_ss"> 
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Linkedin URL</label>
								<div class="col-sm-6">
									<input type="text" class="form-control xyz" id="linkedin_input" placeholder="Linkedin URL" name="linkedin" value="{{ (@$contact) ? @$contact->linkedin : '' }}">
								</div><i class="fa fa-plus-circle" onclick="capture_url(this,'linkedin_input','linkedin_preview','linkedin_ss')"></i>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Linkedin Preivew</label>
								<div class="col-sm-6">
                                    <img src="{{ (@$contact) ? @$contact->linkedin_ss : '' }}" class="pl-3" id="linkedin_preview" width="200px">
                                    <input type="hidden" name="linkedin_ss" value="{{ (@$contact) ? @$contact->linkedin_ss : '' }}" id="linkedin_ss"> 
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Choose List</label>
								<div class="col-sm-6">
									<select id="inputState" class="form-control ppc" name="group" required>
										@foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                        @endforeach
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Notes</label>
								<div class="col-sm-6">
									<textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="notes">{{ (@$contact) ? @$contact->notes : '' }}</textarea>
								</div>
							</div>

							@if(@$contact->audio)
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Audio</label>
								<div class="col-sm-6">
									<audio controls class="w-100">
                                        <source src="{{ @$contact->audio ? URL($contact->audio) : '' }}" type="audio/mpeg">
                                    </audio>
								</div>
							</div>
							@endif

							<div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Record Audio</label>
                                <div class="col-sm-6">
                                    <input type="hidden" value="" id="recorded_file" name="recorded_file">
                                    <div class="recorder_wrapper">
                                        <div class="recorder">
                                            <button type="button" class="record_btn" id="recorder_btn"><i class="fa fa-microphone"></i></button>
                                            <p id="msg_box_contact"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(@$contact->video)
							<div class="form-group row">
								<label for="inputPassword" class="col-sm-2 col-form-label">Video</label>
								<div class="col-sm-6">
									<video controls class="w-100">
                                        <source src="{{ @$contact->video ? URL($contact->video) : '' }}" type="audio/mpeg">
                                    </video>
								</div>
							</div>
							@endif

                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Record Video</label>
                               	<div class="col-md-6"> 
		                            <input type="hidden" value="" id="video_link" name="video">

		                            <div class="video_recorder text-center" onclick="">
		                                <button type="button" class="rcrdbtn" id="video_recorder" recording="0">
		                                    <i class="fa fa-camera" style="color: #1e88e5;font-size: 40px;"></i>
		                                </button>
		                            </div>
		                            <div class="col-md-12 streamer_counter" style="display: none;"> 
				                        <video width="100%"></video>
				                    </div>
		                        </div>
                            </div>

							<div class="text-center">
								<button type="submit" class="btn save-btn ">Save</button>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
						<div class="col-md-6 pull-right">
                            <a href="{{ URL('public/assets/sample_contacts.csv') }}" download style="color:red">Download</a> Sample CSV
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div id="recorder" style="display: none;"></div>
    <div id="msg_box" style="display: none;"></div>
</section>
<script src="{{ URL('public/assets/js/contacts.js') }}"></script>
<script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script>
    $(document).ready(function(){
        $('#m1').addClass('active1');
      
        $('form').submit(function(){
        	if($('form').parsley().isValid()){
	        	hold_on(); 
	        }else{
	        	error("Please fill the form correctly.");
	        }
        });
    });
   
    function filePreview(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#'+id).attr('src',e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function capture_url(btn,input,id,paste_img){
        $(btn).addClass('fa-spin');
		url = $('#'+input).val();
		hold_on();
        $.ajax({
            url: '{{ route("capture_url") }}',
            type:'post',
            data:{'url':url},
            success:function(data){
                $('#'+id).attr('src','data:image/jpeg;base64,'+data);
                $('#'+id).parent('div').show(200);
                $('#'+paste_img).val('data:image/jpeg;base64,'+data);
				$(btn).removeClass('fa-spin');
				hold_off();
            },
            error: function(data){
                alert('Url is not valid.');
                $('#'+id).parent('div').hide(200);
				$(btn).removeClass('fa-spin');
				hold_off();
            }
        })
    }

    function upload_csv(input){
        var file_data = $(input).prop("files")[0];
        var form_data = new FormData(); 
		form_data.append("file", file_data);
		hold_on();
        $.ajax({
            url: "{{ route('upload_csv') }}",
            dataType: 'script',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){
				$('#pills-profile').html(data);
				hold_off();
            }
        });
    }

    function delete_csv(ele){
        row = $(ele).parent('div').parent('td').parent('tr');
        row.remove();
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
				hold_off();
            }    
        });
    }
</script>
<script type="text/javascript">
    var video;
    var recorder;
    var stream;
    var recoder_duration;

    $("#video_recorder").click(async function(){
        btn = this;
        if($(btn).attr('recording')==0){
            
            video = $('.streamer_counter').children('video')[0];
            try{
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true, 
                    audio: true
                });

                setSrcObject(stream, video);
                video.play();
                video.muted = true;
                $('.streamer_counter').hide(200);
                $('#counter_outro').text(3);
                
                // setTimeout(function () {
                //     $('#counter_outro').text(2);
                // }, 1000);
                // setTimeout(function () {
                //     $('#counter_outro').text(1);
                // }, 2000);
   
                setTimeout(function(){
                    $('#counter_outro').hide();
                    recorder = new RecordRTCPromisesHandler(stream,{
                        type: 'video'
                    });

                    // $('.myvideo_outro')[0].play();
                    $("#video_recorder").show(200);

                    $(btn).addClass('recording');

                    $('#recorder_outro').hide(200);
                    $('.streamer_counter').show(200);

                    recorder.startRecording();
                    $(btn).attr('recording','1');
                
                    // duration = (parseInt($('.myvideo_outro').attr("duration")))*1000;

                    recoder_duration = setTimeout(function(){
                        $("#video_recorder").trigger("click");
                    },duration);
                },1000);
            }catch(err){
                alert('There is not device found for recording. Please connect to you camera and refresh you page.');
            }
        }else{
            clearTimeout(recoder_duration);
            //$(btn)[0].disabled = true;
            $(btn).removeClass('recording');

            await recorder.stopRecording();
            let blob = await recorder.getBlob();
            url = await upload_blob_video(blob);
            
            stream.stop();

            $(btn).attr('recording','0');
        }
        
    });    

    function upload_blob_video(blob){
        hold_on();
        var vdo = $('.streamer_counter');
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
               $('#video_link').val(data);
            }    
        });
    }
</script>
@endsection