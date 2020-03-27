@extends('layouts.app')

@section('content')
<section id="content">
	<div class="container-fluid title-bar">
		<div class="row">
			<div class="col-md-4 mt-2">
				<h3 class="page-title pl-3">Suppression List</h3>
			</div>
			<div class="col-md-8 d-flex justify-content-end">
				<div class="row my-row">
					<a href="{{ route('add_suppression') }}">
						<a href="{{ route('add_suppression') }}" class="btn btn-primary btn-lg add-contacts-btn mt-2 mr-5"><i class="fas fa-plus"></i> Add Suppression</a>
					</a>
					<form class="form-inline my-2 search-bar mt-2 ml-4">
						{{-- <input class="form-control mr-sm-2 search-form-control " type="search" placeholder="Search" aria-label="Search">
						<img class="search-icon-img" src="img/search-icon.png"> --}}
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-2 main-side">
		<div class="table-responsive">
			<table class="table tab1 col-md-12">
				<thead>
					<tr>
						<th>Domain Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($suppressions as $suppression)
					<tr>
						<td style="width:50%">{{ $suppression->host_name }}</td>
						<td style="width:20%; border:1px; color:white; solid !important; ">
							{{-- <i class="fas fa-pencil-alt delete"></i>   --}}
							<i class="fa fa-trash delete" onclick="delete_supression(this)" id="{{ Crypt::encrypt($suppression->id) }}"></i>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</section>

<script>
	function delete_supression(button){
		id = $(button).attr('id');
		$.confirm({
			title: 'Confirm',
			content: 'Do you want to delete this suppression ?',
			buttons:{
				Yes:{
					text:'Yes',
					action: function(){
						$.ajax({
							url: '{{ route("delete_suppression") }}',
							type:'post',
							data:{'id':id},
							success: function(data){
								if(data==200){
									$(button).parent('td').parent('tr').hide(500);
								}else{
									$.alert({
										title:'Error',
										content:'Internal server error. Please try after some time.'
									})
								}
							}
						});
					}
				},
				no:{
					text:"No",
				}
			}
		});
	}
</script>
@endsection