
@extends('layouts.app')
@section('content')
<style type="text/css">
	.nav-items-style {
    color: inherit;
    text-decoration: none;
}
i.fas.fa-pencil-alt.delete {
    color: white;
}
.loader {
 display: inline-block;
 width: 100px;
 height: 100px;
 outline: none;
 border-radius: 50%;
 border: none;
 border-right: solid 5px #666;
 border-bottom: solid 5px transparent;
 animation: loader 12s linear infinite;
 -webkit-animation: loader 1s linear infinite;
}
.model_loader {
    text-align: center;
}
@keyframes loader {
 to {
   transform: rotate(360deg);
   -webkit-transform: rotate(360deg);
   -moz-transform: rotate(360deg);
   -ms-transform: rotate(360deg);
   -o-transform: rotate(360deg);
 }
}

@-webkit-keyframes loader {
 to {
   transform: rotate(360deg);
   -webkit-transform: rotate(360deg);
 }
}
</style>
	<section id="content">
		<div class="top_bar">
	        <div class="container-fluid title-bar">
	            <div class="row">
					<div class="col-md-4 mt-2">
						<h3 class="page-title pl-3">My Template</h3>
					</div>
					<div class="col-md-8 d-flex justify-content-end">
						<div class="row my-row">
							<a href="{{ route('unlayer_template') }}" class="btn btn-primary btn-lg add-contacts-btn mt-2 mr-5"><i class="fas fa-plus" aria-hidden="true"></i> Add template</a>
						</div>
					</div>
				</div>
	        </div>
	    </div>
        <div class="clearfix"> </div>
        <div class="bottom_bar">
        	<div class="container-fluid title-bar">
	        	<div class="table-responsive">
					<table class="table tab1 col-md-12">
						<thead>
							<tr>
								<th>Email Template</th>
								<th>Created On</th>
								<th>Preview</th>								
								<th>Action</th>
							</tr>

						</thead>
						<tbody>
							<?php $count = count($email_data); ?>
							@if( $count != 0 )
								@foreach($email_data as $e_data)
									<tr >
										<td>{{$e_data['template_name']}} </td>
										<td>
											{{date("d F y, h:i:s", strtotime($e_data['created_at']))}}
										</td>
										<td><button type="button" class="previewBtn_cont btn btn-primary" data-toggle="modal" data-target="#exampleModalLong" data_id = "{{$e_data['id']}}"> Preview</button>											
										</td>										
										<td style="width:15%; border:1px; color:white; solid !important" data_template = "{{$e_data['id']}}">
			                                <!-- <a href=""><i class="fas fa-pencil-alt delete"></i></a> -->
			                                &emsp; 
			                                <i class="fa fa-trash delete delete_templateBtn"  id="{{ Crypt::encrypt($e_data['id']) }}"></i>
			                            </td>
								    <tr>
								@endforeach
							@endif 
						</tbody>
					</table>
				</div>
	        </div>
        </div>
        <div class="popupModel__Preview modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		  <div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLongTitle">Your Template </h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="model_loader">
		      	
		      </div>
		      <div class="modal-body">
		       
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        
		      </div>
		    </div>
		  </div>
		</div>
    </section>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//preview
		$(document).on('click','.previewBtn_cont',function(){
			
			var view_id = $(this).attr('data_id');
			var current = $(this).parent().find('.modal-body');
			// current.html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
			$('.model_loader').append('<div class="loader"></div>');
			$('.modal-body').hide();
			template_preview(view_id ,current)
			
		});// eo preview
		//delete
		$(document).on('click','.delete_templateBtn',function(){
			var template_id = $(this).parent().attr('data_template');
			var current 	= $(this);
			template_delete(template_id,current);
		});//eo delete
		function template_preview(view_id ,current){
			$.ajax({
                url: '{{ route("get_single_email_templates") }}',
                type:'post',
                data:{'view_id':view_id},
                success:function(data){
                	$('.popupModel__Preview').find('.modal-body').html(data);
                	setTimeout(function(){ 
                		$( ".model_loader" ).empty();
                		$('.modal-body').show();
                	}, 1000);
                	
                	// current.html(data);
                    hold_off();
                    //window.location.replace('{{ route("email_template_list") }}');
                },
                error: function(data){
                    alert('something went wrong.');
                    hold_off();
                }
            });// end ajax
        }
        function template_delete(template_id,current){
        	$.confirm({
	            title: 'Confirm',
	            content:'Do you want to delete the template?',
	            buttons:{
	                yes:{
	                    text:'Yes',
	                    action:function(){
	                    	hold_on();
	                        $.ajax({
				                url: '{{ route("delete_email_template") }}',
				                type:'post',
				                data:{'template_id':template_id},
				                success:function(data){
				                	if ( data == 400) {
				                		alert("We can't delete it because we are using this template in email drip. ");
				                	}else{
				                		current.parent().parent().remove();
				                	}
				                	hold_off();	
				                },
				                error: function(data){
				                    alert('something went wrong.');
				                    hold_off();
				                }
				            });// end ajax
	                    }
	                },
	                no:{
	                    text:'No'
	                }
	            }
	        });
        	
        }
	});
	
</script>
