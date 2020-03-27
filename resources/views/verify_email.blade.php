@extends('layouts.app')
@section('content')


<section id="content" class="authAccount_container">
	<div class="top_bar">
        <div class="container-fluid title-bar">
        	<div class="row">
				<div class="col-md-4 mt-2">
					<h3 class="page-title pl-3">Add your email here</h3>
				</div>
				<div class="col-md-8 d-flex justify-content-end">
					<div class="row my-row">
						<button type="button" class="authAccount__Btn btn btn-primary btn-lg add-contacts-btn mt-2 mr-5" data-toggle="modal" data-target="#authAccount">
						  <i class="fas fa-plus" aria-hidden="true"></i> Add Account
						</button>
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
							<th>Email Address</th>
							<th>Account Type</th>
							<th>Created On</th>	
							<th>Default</th>							
							<th>Action</th>
						</tr>

					</thead>
					<tbody>
						@foreach($data as $auth_value)
							<tr>
								<td>{{$auth_value->email}}</td>
								<td style="text-transform: capitalize;" ><i class="fa fa-envelope" aria-hidden="true"></i> {{$auth_value->provider}}</td>
								<td>{{$auth_value->created_at}}</td>
                                <td>
								@if($auth_value->set_default == 1) <i class="fa fa-circle" style="color:green;" aria-hidden="true"></i> Running @else<i class="fa fa-circle" style="color:grey;" aria-hidden="true"></i> Stopped @endif </td> 
								<td style="width:20%; border:1px; color:white; solid !important">
                                    <i class="fa fa-trash delete" onclick="revoke(this)" id="{{ Crypt::encrypt($auth_value->account_id) }}" aria-hidden="true"></i>

                                    @if($auth_value->set_default != 1)
                                    <div class="extraDropIcon btn-group dropleft show">
                                        <i class="fas more-icon fa-ellipsis-h pr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" aria-hidden="true"></i>
                                        
                                        <div class="dropdown-menu contact-dropdown-menu " x-placement="left-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-158px, -4px, 0px);">
                                            <a class="dropdown-item" onclick="setDefault(this)" id="{{ Crypt::encrypt($auth_value->id) }}" >Set as default</a>
                                            
                                        </div>
                                    </div>
                                
                                    @endif
                                </td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="authAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<h3>Add Your Account </h3>
      	<div class="form__containerAuth">
      		<form id="authAccountForm" class="_ri7fs8"><input type="hidden" class="token" name="_token" value="{{ csrf_token() }}"><div class="_efm02w"><div class="_e615pd-o_O-_efm02w"><label for="emailAddress-Email Address" class="_ka4fwz">Email Address</label><input type="email" name="emailAddress" title="Email Address" id="emailAddress-Email" aria-label="Email Address" label="Email Address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" class="_1snbej0"><div class="_8lgj3i"></div></div></div></form>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="Authenticate__account btn btn-primary" onclick="authenticateAcount(this)" >Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="hide naylas__resposne">
	
</div>
<!-- EO Modal -->
</section>
<style type="text/css">
	.form__containerAuth {
	    padding: 25px 15px;
	}
	div#authAccount .modal-body h3 {
	    text-align: center;
	}
	form#authAccountForm label._ka4fwz {
	    width: 100%;
	        margin: 0;
	}
	form#authAccountForm input#emailAddress-Email {
	    width: 100%;
	    padding: 5px;
	}
    .extraDropIcon i {
    color: black;
    }
</style>

<script type="text/javascript">
	function authenticateAcount(ele){
		var flag = 0;
		var email_address = jQuery('input#emailAddress-Email').val();
		var _token        = jQuery('.token').val();
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (email_address == '') {
            flag++;
        }
	  	if(!regex.test(email_address)) {
		    flag++;
		}

        if (flag >0) { 
        	alert('Enter a valid email.');
        	jQuery('input#emailAddress-Email').css('border-color','red');
            $('html, body').animate({
                scrollTop: $("input#emailAddress-Email").offset().top
            }, 2000);
            return false;
        }else{
        	jQuery('input#emailAddress-Email').css('border-color','green');
        	$.ajax({
                url: '{{ route("oauth_authorize") }}',
                type:'post',
                data:{'email':email_address,'_token':_token},
                success:function(data){
                	hold_on();
                    jQuery('.naylas__resposne').append(data);
                    var url = jQuery('.naylas__resposne a').attr('href');
                    window.location.replace(url);
                    //alert('okk');
                },
                error: function(data){
                    alert('something went wrong.');
                    //hold_off();
                }
            });
        }
	}
	function revoke(ele){
		var id          = $(ele).attr('id');
       // hold_on();
		$.ajax({
            url: '{{ route("revoke_session") }}',
            type:'post',
            data:{'id':id},
            success:function(data){
                if ( data == 1) {
                    $(ele).parent('td').parent('tr').hide(500);
                }else if(data == 400){
                    alert('atleast one email account require.');
                }else if(data == 400){
                    alert("Can't delete default selected email.");
                }else{
                    alert('something went wrong.');
                }
            },
            error: function(data){

                alert('something went wrong.');
            }
        });
	}
    function setDefault(ele){
        var id          = $(ele).attr('id');
        $.confirm({
                title: 'Confirm',
                content:'Do you want to set this email as default email?',
                buttons:{
                    yes:{
                        text:'Yes',
                        action:function(){
                            hold_on();
                            $.ajax({
                                url: '{{ route("set_default") }}',
                                type:'post',
                                data:{'id':id},
                                success:function(data){
                                    location.reload();
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
</script>
@endsection