@extends('layouts.app')

@section('content')
<section id="content">
	<div class="container-fluid title-bar">
		<div class="row">
			<div class="col-md-4 mt-2">
				<h3 class="page-title pl-3">Add email/domains to Suppression</h3>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-4 main-side pb-5 pt-5">
		<div class="row">
			<div class="col-md-12">
				<p>Email addresses included in a suppression list will never be emailed even if they are still subscribed to a contact ist.</p>
                <form class="" method="POST" action="{{ route('save_suppression') }}">
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-4 col-form-label">Suppress Contacts From</label>
						<div class="col-sm-5">
                            <select id="inputState" class="form-control" name="group_id" required>
                                @foreach($groups as $group)
                                <option value="{{  $group->id }}">{{  $group->group_name }}</option>
                                @endforeach
                            </select>
                        </div>
					</div>
					<div class="form-group row">
						<label for="inputPassword" class="col-sm-4 col-form-label">Email or Domains to Suppress</label>
						<div class="col-sm-5">
							<textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="host_name" required></textarea>
						</div>
					</div>
					<div class="row d-flex justify-content-center mt-5">
                        <button type="submit" class="btn preview-btn ">Save</button>
                        <a href="{{ route('suppression') }}">
                            <button type="button" class="btn preview-btn ml-5">Cancel</button>
                        </a>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(function(){
        $('#m6').addClass('active1');
    });
</script>
@endsection