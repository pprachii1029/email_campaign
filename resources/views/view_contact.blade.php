@extends('layouts.app')

@section('content')
<section id="content">
	<div class="container-fluid title-bar">
		<div class="row">
			<div class="col-md-6 mt-2">
				<h3 class="page-title pl-3">{{ $data->first_name }} {{ $data->last_name }}</h3>
			</div>
			<div class="col-md-6 d-flex justify-content-center">
				<button type="button" class="btn btn-primary btn-lg add-contacts-btn mt-2 ml-3" onclick="move_contact(this)" contact_id="{{ Crypt::encrypt($data->id) }}">
                    <i class="fas fa-arrows-alt"></i> Move Contacts
                </button>
                <button type="button" onclick="refresh(this)" class="btn btn-primary btn-lg add-contacts-btn mt-2 ml-3">
                    <i class="fas fa-sync"></i> Refresh Contacts
                </button>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-4 main-side pb-3">
		<div class="profile">
			<h3> Personal Details</h3>
			<table class="table tab1 table-responsive">
				<thead>
					<tr>
						<th scope="col">Image</th>
						<th scope="col">Name</th>
						<th scope="col">Phone #</th>
						<th scope="col">Contact List</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th style="width: 25%;align-items: center;">
							<img class="contact-img" src="{{ ($data->picture) ? $data->picture : URL('public/assets/img/john-doe-profile.png') }}">
						</th>
						<td style="width:25%;">{{ $data->first_name }} {{ $data->last_name }}</td>
						<td style="width:25%;font-size: 16px;">{{ $data->phone_number }}</td>
						<td style="width:25%">{{ $group_name }}</td>
                        <td style="width:25%; border:1px; color:white; solid !important">
                            <a href="{{ route('edit_contact',['contact_id'=>Crypt::encrypt($data->id)]) }}">
                                <i class="fas fa-pencil-alt delete"></i> 
                            </a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="profile1">
			<h3> Social Contact Details</h3>
			<table class="table tab1 table-responsive">
				<thead>
					<tr>
						<th scope="col">Image</th>
						<th scope="col">Url</th>
						<th scope="col">Website Preview</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th style="width: 30%;align-items: center;">Website</th>
						<td style="width:30%;">{{ $data->website }}</td>
						<td style="width:35%;">
							<img src="{{ $data->website_ss }}" class="img-fluid">
						</td>
                        <td style="width:35%; border:1px; color:white; solid !important">
                            <a href="{{ route('edit_contact',['contact_id'=>Crypt::encrypt($data->id)]) }}">
                                <i class="fas fa-pencil-alt delete"></i> 
                            </a>
						</td>
					</tr>
					<tr>
						<th style="width: 30%;align-items: center;">Facebook</th>
						<td style="width:30%;">{{ $data->facebook }}</td>
						<td style="width:35%;">
							<img src="{{ $data->facebook_ss }}" class="img-fluid">
						</td>
                        <td style="width:35%; border:1px; color:white; solid !important">
                            <a href="{{ route('edit_contact',['contact_id'=>Crypt::encrypt($data->id)]) }}">
                                <i class="fas fa-pencil-alt delete"></i> 
                            </a>
						</td>
					</tr>
					<tr>
						<th style="width: 30%;align-items: center;">Linkedin</th>
						<td style="width:30%;">{{ $data->linkedin }}</td>
						<td style="width:35%;">
							<img src="{{ $data->linkedin_ss }}" class="img-fluid">
						</td>
                        <td style="width:35%; border:1px; color:white; solid !important">
                            <a href="{{ route('edit_contact',['contact_id'=>Crypt::encrypt($data->id)]) }}">
                                <i class="fas fa-pencil-alt delete"></i> 
                            </a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>
<script>
    function refresh(ele){
        $(ele).children('i').addClass('fa-spin');
        setTimeout(function(){
            $(ele).children('i').removeClass('fa-spin');
            location.reload();
        },2000);
    }

    function move_contact(ele){
        contact_id  = $(ele).attr('contact_id');
        $.alert({
            columnClass: 'col-md-6 col-md-offset-3',
            title:"Select where you want to move this contact.",
            content: function(){
                pop = this;
                $.ajax({
                    url: "{{ route('move_contact') }}",
                    type:'post',
                    data:{'contact_id':contact_id},
                    success:function(data){
                        pop.setContent(data);
                    }
                });
            },
            buttons:{
                close:{
                    text:'Close'
                }
            }
        });
    }
</script>
@endsection