@extends('layouts.app')
@section('content')
<style type="text/css">
	.groupSequance_Container{ display: flex; }
</style>
	<section id="content" class="emailDrip_automationContainer">
		<div class="top_bar">
	        <div class="container-fluid title-bar">
	        	<div class="row">
					<div class="col-md-4 mt-2">
						<h3 class="page-title pl-3">Your Automations Emails</h3>
					</div>
					<div class="col-md-8 d-flex justify-content-end">
						<div class="row my-row">
							
						</div>
					</div>
				</div>
	        </div>
	    </div>
	    <div class="clearfix"> </div>
	    <div class="bottom_bar">
			<div class="container-fluid title-bar">
				   <!-- eo append -->

				<div class="text-center mt-5 mail_automationInput">
					<div class="row">
						<div class="col-md-8">
							<label for="fname" class="check-label">Campaign Name</label>
							<input type="text" id="automation_name" name="automation_template" class="input-check" placeholder="Demo Automation">
						</div>
						@if ( !empty($contacts))
						<div class="col-md-4">
							<div class="user_selection" >
								<div class="choose">
									<p>Choose Contacts</p>
				                </div>
								<div class="dropdown">
										@foreach( $contacts as $contact)
										
										 @if( $contact->picture)
										  	<a class="selected_groupView  btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink
										  	" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										  		<input type="hidden" class="hide" name="contact[]" value="{{$contact->id}}">
									            <img class="contact-img drop-img" src="{{$contact->picture}}">{{$contact->group_name}}
									        </a>
										 @else
											<a class="selected_groupView btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									            <input type="hidden" class="hide" name="contact[]" value="{{$contact->id}}">
									            <img class="contact-img drop-img" src="https://videoemailpro.com/public/images/defaultUser.jpg">{{$contact->group_name}}
									        </a>
										 @endif
										 @break
										@endforeach

								    <div class="dropdown-menu hr" aria-labelledby="dropdownMenuLink" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -73px, 0px); top: 0px; left: 0px; will-change: transform;">
								        @foreach( $contacts as $key => $contact)

									        @if( $contact->picture)
											  	<a class="dropdown-item  @if($key == 0) active  @endif " >
											  		<input type="hidden" class="hide" name="contact[]" value="{{$contact->id}}">
										            <img class="contact-img drop-img" src="{{$contact->picture}}">{{$contact->group_name}}
										        </a>
											@else
												<a class="dropdown-item  @if($key == 0) active   @endif " >
													<input type="hidden" class="hide" name="contact[]" value="{{$contact->id}}">
										            <img class="contact-img drop-img" src="https://videoemailpro.com/public/images/defaultUser.jpg">{{$contact->group_name}}
										        </a>
											@endif
										@endforeach
								        
								    </div>
								</div>
							</div>
						</div>
						@endif
					</div>
					<div class="clearfix"></div>
					<div class=" automation_startBtn">
						<div class="wrapper"><button type="button" class="hide btn btn-primary saveAndStart">Save & Start</button></div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="automation_groups">
					
				</div>
				<div class="wrapper mt-5">
					<button type="button" class="btn btn-primary exampleModal" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#exampleModalCenter">
							Next
					</button>
					<button type="button"  id="save_itBtn" class="btn btn-primary hide" disabled >
							Save it
					</button>
				</div>
					<!-- Modal -->
				<div class="modal fade modelCheck" id="exampleModalCenter"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
				  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
					<div class="modal-content">
						@if(!empty($email_template) && !empty($video_template) && !empty($contacts) && !empty($accounts))
						<form id="regForm" action="">
									<!-- One "tab" for each step in the form: -->
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Email template</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
								<div id="email_Tab" class="tab email_Tab">
									<!-- <div class="modal-header"><h5 class="modal-title" id="exampleModalLongTitle">Select Your Email Template</h5></div> -->
									<div class="row">
										@foreach( $email_template as $key=>$e_template)
										<div class="col-sm-4">
											<label>
											  <input type="radio" class="email_template" name="email_template" data_name ="{{$e_template->template_name}}" value="{{$e_template->id}}" checked>
											  @if($e_template->template_image)
											  	<img src="{{$e_template->template_image }}" width="100%" >
											  @else
											  	<img src="/public/images/defaultthumbnail.jpg" width="100%" >
											  @endif 
											  
											</label>
											<p>{{$e_template->template_name}}</p>
										</div>
										@endforeach
									</div>
								</div>
								<div id="video_Tab" class="tab video_Tab">
									<!-- <div class="modal-header"><h5 class="modal-title" id="exampleModalLongTitle">Select Your  Video Template</h5></div> -->
									<div class="row">
										@foreach( $video_template as $v_template)
											
											<div class="col-sm-4">
												<label>

												  @if($v_template['final_video'] != '')
												  <input type="radio" class="video_template" name="video_template" value="{{$v_template['id']}}" checked data_type="video" data_name="{{$v_template['title']}}">
												  	<video src="{{$v_template['final_video']}}" width="100%"></video>
												  @else
												  <input type="radio" class="video_template" name="video_template" value="{{$v_template['id']}}" checked data_type="img" data_name="{{$v_template['title']}}">
												  	<img src="{{URL::to('/')}}/public/assets/img/seo-5.png" width="100%" >
												  @endif
												  
												</label>
												<p>{{$v_template['title']}}</p>
											</div>
											
										@endforeach
									</div>
								</div>
								
								<div  class="buttonFooter_nav" style="overflow: :hidden;">
									<div  class="footerNav" style="float:right;">
										<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
										<button type="button" id="nextBtn" onclick="nextPrev(1)" >Next</button>
										<a id="submitBtn" class="submitBtn" style="display: none;">submit</a>
									</div>
								</div>
							<!-- Circles which indicates the steps of the form: -->
								 <div style="text-align:center;margin-top:40px;">
									<span class="step"></span>
									<span class="step"></span>
								 </div>
							
						  </div>
						</form>
						@else
							<div class="noData__Found">
								<div class="modal-header">
									
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									  <span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="container requiredAlert__messaage">
									<h5 class="modal-title top_Line" >Before you will start making the automation</h5>
									<p class="bottom_Line">Please keep in mind that all the listed below points are required. </p>
									<ul class="alertContent_below">
										@php $i= 1;@endphp
										@if( empty($email_template))
										
											<li>
												<a class="warnning__ContentBtn" href="{{URL('unlayer_template')}}" target="_blank"><span class="countLines">{{$i++}}.</span><i class="fas fa-envelope" aria-hidden="true"></i><br> Do you have you created email templates?</a>
											</li>
										@endif
										@if( empty($video_template))
										<li>
											<a class="warnning__ContentBtn" href="{{URL('add_template')}}" target="_blank"><span class="countLines">{{$i++}}.</span><i class="fas fa-video"></i><br> Do you have you created video templates?</a>
										</li>
										@endif
										@if( empty($contacts))
										<li>
											<a class="warnning__ContentBtn" href="{{URL('add_group')}}" target="_blank"><span class="countLines">{{$i++}}.</span><i class="far fa-address-card" aria-hidden="true"></i><br> Do you have contacts?</a>
										</li>
										@endif
										@if( empty($accounts))
										<li>
											<a class="warnning__ContentBtn" href="{{URL('verify_email')}}" target="_blank"><span class="countLines">{{$i++}}.</span><i class="fa fa-users" aria-hidden="true"></i><br> Have you connected your account?</a>
										</li>
										@endif
									</ul>
								</div>
							</div>
						@endif
					</div>
				  </div>
				</div>
			</div>
	    </div>
	</section>
<!-- popup -->
<div class="modal fade" id="timeModel" tabindex="-1" role="dialog" aria-labelledby="timeModelCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document" style="padding-left:0;">
		<div class="modal-content modal-color-trans">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="cross-weight" aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body body-background">
				<form class="timeForm">
					<div class="row" style="align-items:baseline;">
						<div class="col-md-2">
							<h5 style="font-weight:400;font-size:17px;"> Wait </h5>
						</div>
						<div class="col-md-3">
							<input type="number" class="waitSelected" id="waitSelected" name="wait" min="1" value="1" style="width:100%;"/>
						</div>
						<div class="col-md-5">
							<div class="input-group mb-3">
								<select class="custom-select" name="object" class="objectTimeSelected" id="inputGroupSelect_timeObject">
									<option value="year">Year</option>
									<option value="month">month</option>
									<option value="week">week
									</optiom>
									<option value="day" selected>day
									</optiom>
									<option value="hour">hour</option>
									<option value="minute">minute</option>
									
								</select>
							</div>
						</div>
						<div class="col-md-2"></div>
					</div>
					<div class="inputField_Time activeTime row" style="align-items:baseline;">
						<label> send at 
							<select id="modalSendTime" name="waitTime" class="waitTime">
                                <option value="0" selected>12:00 am</option>
                                <option value="30">12:30 am</option>
                                <option value="60">1:00 am</option>
                                <option value="90">1:30 am</option>
                                <option value="120">2:00 am</option>
                                <option value="150">2:30 am</option>
                                <option value="180">3:00 am</option>
                                <option value="210">3:30 am</option>
                                <option value="240">4:00 am</option>
                                <option value="270">4:30 am</option>
                                <option value="300">5:00 am</option>
                                <option value="330">5:30 am</option>
                                <option value="360">6:00 am</option>
                                <option value="390">6:30 am</option>
                                <option value="420">7:00 am</option>
                                <option value="450">7:30 am</option>
                                <option value="480">8:00 am</option>
                                <option value="510">8:30 am </option>
                                <option value="540">9:00 am</option>
                                <option value="570">9:30 am</option>
                                <option value="600">10:00 am</option>
                                <option value="630">10:30 am</option>
                                <option value="660">11:00 am</option>
                                <option value="690">11:30 am</option>
                                <option value="720">12:00 pm</option>
                                <option value="750">12:30 pm</option>
                                <option value="780">1:00 pm</option>
                                <option value="810">1:30 pm</option>
                                <option value="840">2:00 pm</option>
                                <option value="870">2:30 pm</option>
                                <option value="900">3:00 pm</option>
                                <option value="930">3:30 pm</option>
                                <option value="960">4:00 pm</option>
                                <option value="990">4:30 pm</option>
                                <option value="1020">5:00 pm</option>
                                <option value="1050">5:30 pm</option>
                                <option value="1080">6:00 pm</option>
                                <option value="1110">6:30 pm</option>
                                <option value="1140">7:00 pm</option>
                                <option value="1170">7:30 pm</option>
                                <option value="1200">8:00 pm</option>
                                <option value="1230">8:30 pm </option>
                                <option value="1260">9:00 pm</option>
                                <option value="1290">9:30 pm</option>
                                <option value="1320">10:00 pm</option>
                                <option value="1350">10:30 pm</option>
                                <option value="1380">11:00 pm</option>
                                <option value="1410">11:30 pm</option>
                        	</select>
                        <!-- <input type="text" name="Time" class="waitTime">-->
                   		</label>
					</div>
				</form>
				<button type="button" class="btn btn-primary saveTime">Save changes</button>
			</div>
		</div>
	</div>
</div>
<!-- endpopup -->

<style type="text/css">
	.automationStep {
	    display: flex;
	    text-align: center;
	    padding: 15px;
	}
	span.selectedTime_show strong {
	    padding: 0px 2px;
	}
</style>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
<script >
	jQuery(window).on('load',function(){
		$("#button#nextBtn").attr("disabled", true);
	});
	var current = 0;
	var addedDives = 0;
	var totalArr 	= [];
	jQuery(document).on('click','button.changeTime',function(){
		var currentArr = jQuery(this).attr('data-type');
		current = currentArr;
		
	});
	jQuery("select#inputGroupSelect_timeObject").on('change',function(){
        var selectedCountry = $(this).children("option:selected").val();
        if( selectedCountry == 'minute' || selectedCountry == 'hour' ){
			jQuery('.inputField_Time').removeClass('activeTime');
		}else{
			jQuery('.inputField_Time ').addClass('activeTime');
		}
    });
	jQuery(document).on('click','.saveTime',function(){
		var timeCont = ''; 
		var time  = { };
	    var value = $("form.timeForm").serializeArray();

		$.each($('form.timeForm').serializeArray(), function() {
			var index = current -1; 
			var vall  = jQuery('select#modalSendTime option:selected ').text();

			if ( this.name ==  'wait') {
				totalArr[index].wait = this.value;
				timeCont+= '<strong> '+ this.value +' </strong><input type="text" class="hide" name="wait[]" value="'+this.value+'"> ';
			}
			if ( this.name ==  'object') {
				totalArr[index].object = this.value;
				timeCont+= '<strong> '+ this.value +' </strong><input type="text" class="hide" name="object[]" value="'+this.value+'"> ';
			}
			if( this.name == 'waitTime'){
				if ( jQuery('.inputField_Time').hasClass('activeTime')) { 
					totalArr[index].waitTime = this.value; timeCont+= ' and send at <strong> '+vall+'</strong>.<input type="text" class="hide" name="waitTime[]" value="'+this.value+'">';
				}else{ 
					totalArr[index].waitTime = 'n';
					timeCont+= '<input type="text" class="hide" name="waitTime[]" value="n">';
				}	
			}
			
		});
		// console.log(totalArr);
	    var cls = '.section_'+current;
	   jQuery(cls).find('span.selectedTime_show').html(timeCont); 
	   $( "button.close" ).trigger( "click" );
	});

	$('#prevBtn').on('click',function(){
		$('#exampleModalLongTitle').html('Email template');
	});
	
	$(document).on('click','#nextBtn',function(){

		$('#exampleModalLongTitle').html('Video template');
	});
	
	var currentTab = 0; // Current tab is set to be the first tab (0)
	showTab(currentTab); // Display the current tab

	function showTab(n) {
	  // This function will display the specified tab of the form...
	  var x = document.getElementsByClassName("tab");
	  x[n].style.display = "block";
	  //... and fix the Previous/Next buttons:
	  if (n == 0) {
	    document.getElementById("prevBtn").style.display = "none";
	  } else {
	    document.getElementById("prevBtn").style.display = "inline";
	  }
	  if (n == (x.length - 1)) {
	    document.getElementById("nextBtn").style.display = "none";
	    document.getElementById("submitBtn").style.display = "block";
	  } else {
	    document.getElementById("nextBtn").innerHTML = "Next";
	    document.getElementById("nextBtn").style.display = "block";
	    document.getElementById("submitBtn").style.display = "none";
	  }
	  //... and run a function that will display the correct step indicator:
	  fixStepIndicator(n)
	}

	function nextPrev(n) {
	  var x = document.getElementsByClassName("tab");
	  if (n == 1 && !validateForm()) return false;
	  x[currentTab].style.display = "none";
	  currentTab = currentTab + n;
	  if (currentTab >= x.length) {
	    // ... the form gets submitted:
	    //document.getElementById("regForm").submit();
	    return false;
	  }
	  showTab(currentTab);
	}

	function validateForm() {
	  var x, y, i, valid = true;
	  x = document.getElementsByClassName("tab");
	  y = x[currentTab].getElementsByTagName("input");
	  for (i = 0; i < y.length; i++) {
	    if (y[i].value == "") {
	      y[i].className += " invalid";
	      valid = false;
	    }
	  }
	  if (valid) {
	    document.getElementsByClassName("step")[currentTab].className += " finish";
	  }
	  return valid; // return the valid status
	}

	function fixStepIndicator(n) {
	  // This function removes the "active" class of all steps...
	  var i, x = document.getElementsByClassName("step");
	  for (i = 0; i < x.length; i++) {
	    x[i].className = x[i].className.replace(" active", "");
	  }
	  x[n].className += " active";
	}


//append
	
	jQuery('a#submitBtn').on('click',function(){
		var flag 	= 0;
		var result  = { };
		var dt 		= new Date();
		var time 	= dt.getHours() + ":" + dt.getMinutes() ;
		//jQuery('.waitTime').val(time);

		if (totalArr.length > 0) {
		   jQuery('#save_itBtn').removeClass('hide').removeAttr("disabled");
		   jQuery('.saveAndStart').removeClass('hide').removeAttr("disabled");
		}else{
			$('#save_itBtn').addClass('hide').attr("disabled");
			$('.saveAndStart').addClass('hide').attr("disabled");
		}
		
		//check
		var value 		= $("form").serializeArray();
		var defaultDate = "{{date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' +1 day'))}}";
		
		$.each($('form').serializeArray(), function() {
		    result[this.name] = this.value;
		});
		if ( jQuery(result['email_template'] ) == '') {
			alert('Please select email template');
			flag++;
		}
		if ( jQuery(result['video_template'] ) == '') {
			alert('Please select video template');
			flag++;
		}
		if ( flag > 0) {
			return false;
		}
		result['send_at'] = defaultDate;
		totalArr.push(result);

		if (totalArr.length > 0) {
		   jQuery('#save_itBtn').removeClass('hide').removeAttr("disabled");
		   jQuery('.saveAndStart').removeClass('hide').removeAttr("disabled");
		}else{
			$('#save_itBtn').addClass('hide').attr("disabled");
			$('.saveAndStart').addClass('hide').attr("disabled");
		} 
		if (jQuery('div#exampleModalCenter').hasClass('editIt')) {
			var arrVal	  = sequenceNo;
		}else{
			var arrVal	  = totalArr.length;
			
		}
		append_sequence(arrVal);
	});

	var sequenceNo = '';
	jQuery(document).on('click','.editSequence',function(){
		jQuery('.modelCheck').addClass('editIt');
		var value 		= jQuery(this).attr('data_sequence');
		sequenceNo  = value;
		jQuery('.exampleModal').trigger('click');
		
	});
	jQuery(document).on('click','button.close ,a#submitBtn',function(){
		if (jQuery('div#exampleModalCenter').hasClass('editIt')) {
			jQuery('div#exampleModalCenter').removeClass('editIt');
		}
	});
	jQuery(document).on('click','.deleteSeq',function(){
		var value 		= jQuery(this).attr('item');
		totalArr.pop();
		jQuery('.'+value).remove();
		if (totalArr.length > 0) {
		   jQuery('#save_itBtn').removeClass('hide').removeAttr("disabled");
		   jQuery('.saveAndStart').removeClass('hide').removeAttr("disabled");
		}else{
			$('#save_itBtn').addClass('hide').attr("disabled");
			$('.saveAndStart').addClass('hide').attr("disabled");
		} 
	});
	function append_sequence(arrVal){
		var base_url  = "{{URL::to('/')}}";
		var eTemplate = jQuery('input.email_template:checked').parent().find('img').attr('src');
		var type 	  = jQuery('input.video_template:checked').attr('data_type');
		var eName     = jQuery('input.email_template:checked').attr('data_name');
		var vName     = jQuery('input.video_template:checked').attr('data_name');
		//endchecck	
		
		
		html = '';
		if ( arrVal == totalArr.length) {
			addedDives++;
			arrVal = addedDives;
			html+='<div class="appendDiv_container section_'+arrVal+' " data-type="'+arrVal+'">';
			html+='<div class="mondelPaprent row" style="margin-left:15px; margin-right:15px;">';
			html+='<div class=" contentOfTime col-md-12 pt-3">';
			html+='<p> wait';
			html+='<span class="selectedTime_show"> <strong>  '+jQuery('input#waitSelected').val()+' '+ jQuery('select#inputGroupSelect_timeObject option:selected').val()+'  </strong>';
			html+='<input type="text" class="hide" name="wait[]" value="'+jQuery('input#waitSelected').val()+'"><input type="text" class="hide" name="object[]" value="'+ jQuery('select#inputGroupSelect_timeObject option:selected').val()+'">';
			if (jQuery('.inputField_Time').hasClass('activeTime')) {
				html+='<input type="text" class="hide" name="waitTime[]" value="'+ jQuery('select#modalSendTime option:selected').val()+'">';
				html+='  and send at  <strong>'+ jQuery('select#modalSendTime option:selected').text()+'</strong>';
			}else{
				html+='<input type="text" class="hide" name="waitTime[]" value="">';
			}
			html+='</span>';
			html+='<button class="btn btn-primary py-1 px-7 changeTime" type="submit" data-toggle="modal" data-target="#timeModel" data-type="'+arrVal+'"> <i class="fa fa-pencil"></i> &nbsp; Edit</button>';
			html+='</p>';
			html+='</div>';
			html+='</div>';
			html+='<div class="groupSequance_Container py-3" style="background:#efefef;">';
			html+='<div class="col-md-8 groupSequance " >';
			html+='<div class="col-md-3 col-12">';
			html+='<input type="text" class="hide" name="emilTemp[]" value="'+jQuery('input.email_template:checked').val()+'">';
			html+='<img src="'+eTemplate+'" width="100%" alt="">';
			html+='</div>';
			html+='<div class="col-md-4 col-12">';
			html+='<input type="text" class="hide" name="videoTemp[]" value="'+jQuery('input.video_template:checked').val()+'">';
			if( type == 'video'){
				var vTemplate = jQuery('input.video_template:checked').parent().find('video').attr('src');
				html+='<video src="'+vTemplate+'" width="100%" alt=""></video>';
			} else{
				var vTemplate = jQuery('input.video_template:checked').parent().find('img').attr('src');
				html+='<img src="'+vTemplate+'" width="100%" alt="">';
			}
			
			html+='</div>';
			html+='<div class="col-md-5 col-6">';
			html+='<h4> '+eName+' </h4>';
			html+='<p>'+vName+'</p>';
			html+='</div>';

			html+='</div>';
			html+='<div class="col-md-4" >';
			html+='<button type="button" class="btn btn-outline-secondary btnfunc editSequence" data_sequence="'+arrVal+'" >Change</button>';
			html+='<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split btnfunc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="sr-only">Toggle Dropdown</span>';
			html+='</button>';
			html+='<div class="dropdown-menu"><a class="dropdown-item deleteSeq" item ="section_'+arrVal+'" >Delete</a></div>';
			html+='</div>';
			html+='</div>';
			html+='</div>';

			jQuery('.automation_groups').append(html);
		}else{
			html+='<div class="col-md-8 groupSequance " >';
			html+='<div class="col-md-3 col-12">';
			html+='<input type="text" class="hide" name="emilTemp[]" value="'+jQuery('input.email_template:checked').val()+'">';
			html+='<img src="'+eTemplate+'" width="100%" alt="">';
			html+='</div>';
			html+='<div class="col-md-4 col-12">';
			html+='<input type="text" class="hide" name="videoTemp[]" value="'+jQuery('input.video_template:checked').val()+'">';
			if( type == 'video'){
				var vTemplate = jQuery('input.video_template:checked').parent().find('video').attr('src');
				html+='<video src="'+vTemplate+'" width="100%" alt=""></video>';
			} else{
				var vTemplate = jQuery('input.video_template:checked').parent().find('img').attr('src');
				html+='<img src="'+vTemplate+'" width="100%" alt="">';
			}
			
			html+='</div>';
			html+='<div class="col-md-5 col-6">';
			html+='<h4> '+eName+' </h4>';
			html+='<p>'+vName+'</p>';
			html+='</div>';

			html+='</div>';
			html+='<div class="col-md-4" >';
			html+='<button type="button" class="btn btn-outline-secondary btnfunc editSequence" data_sequence="'+arrVal+'" >Change</button>';
			html+='<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split btnfunc" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="sr-only">Toggle Dropdown</span>';
			html+='</button>';
			html+='<div class="dropdown-menu"><a class="dropdown-item deleteSeq" item ="section_'+arrVal+'" >Delete</a></div>';
			html+='</div>';
			var suspect = '.section_'+arrVal+' .groupSequance_Container';
			jQuery(suspect).html(html);
		}
		

		reset();
	}
	function reset(){
		$( "button.close" ).trigger( "click" );
		 jQuery('div#video_Tab').css('display','none');
		 $('#exampleModalLongTitle').html('Email template');
        currentTab = 0; // Current tab is set to be the first tab (0)
		showTab(currentTab);
	}
	//validate
	jQuery('input#automation_name').on('keyup',function(){
		var val = jQuery(this).val();
		if (val == '' ) {
			jQuery('input#automation_name').css('border-color','red');
		}else{
			jQuery('input#automation_name').css('border-color','#aaaaaa');
		}
	});

	//last to save
	jQuery('button#save_itBtn').on('click',function(){
		var start = 0;
		save_sequence(start);
	});
	jQuery('.saveAndStart').on('click',function(){
		var start = 1;
		save_sequence(start);
	});
	function save_sequence(start) {
		var eTemp 		= jQuery("input[name='emilTemp[]']").map(function(){return jQuery(this).val();}).get().join();
        var vTemp 		= jQuery("input[name='videoTemp[]']").map(function(){return jQuery(this).val();}).get().join();
        var wait  		= jQuery("input[name='wait[]']").map(function() {return jQuery(this).val();}).get().join();
        var object 		= jQuery("input[name='object[]']").map(function(){return jQuery(this).val();}).get().join();
        var waitTime 	= jQuery("input[name='waitTime[]']").map(function(){return jQuery(this).val();}).get().join();
        var contact     = jQuery(".selected_groupView input[name='contact[]']").val();

        // console.log('--eTemp--');
        // console.log(eTemp);
        // console.log('--vTemp--');
        // console.log(vTemp);
        // console.log('--wait--');
        // console.log(wait);
        // console.log('--object--');
        // console.log(object);
        // console.log('--waitTime--');
        // console.log(waitTime);
        // console.log('--contact--');
        // console.log(contact);
        // return false;
        if( start == '' ){var start       = 0;}

		var flag = 0 ;
		var automation_category = jQuery('input#automation_name').val();
		if (automation_category == '') {
			alert('please enter the automation name. ');
			jQuery('input#automation_name').css('border-color','red');
				$('html, body').animate({
			        scrollTop: $("#automation_name").offset().top
			    }, 2000);
			flag++;
		}

		if(jQuery('.automation_groups .appendDiv_container').length == 0){
			alert('please add your sequence email.');
			flag++;
		}

		if (flag >0) { return false;}
		$.ajax({
	        url: '{{ route("create_automation_groups") }}',
	        type:'post',
	        data:{'template_name':automation_category,'email_templates': eTemp,'video_templates':vTemp,'wait':wait,'object':object,'waitTime':waitTime,
	        'contact':contact,'start':start},
	        success:function(data){
	            hold_off();
	            window.location.replace('/sent_campaigns#tab2');
	        },
	        error: function(data){
	            alert('something went wrong.');
	            hold_off();
	        }
	    });
	}
	//$('#exampleModalCenter').modal({backdrop: 'static', keyboard: false})  
</script>
@endsection

