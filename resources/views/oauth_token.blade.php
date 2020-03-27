
@extends('layouts.app')
@section('content')

<script type="text/javascript">
	jQuery(window).on('load',function(){
		hold_on();
		setTimeout(function(){ 
			window.location.replace('{{route("verify_email")}}'); 
		}, 3000);
	});
</script>
@endsection