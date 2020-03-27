@extends('layouts/app')

@section('content')
<section id="content">
	<div class="container-fluid title-bar">
		<div class="row">
			<div class="col-md-4 mt-2">
				<h3 class="page-title pl-3">Notifications</h3>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-4 main-side pb-3 pt-3">
		<table class="table tab1 table-responsive">
			<thead>
				<tr>
					<th scope="col">Image</th>
					<th scope="col">Notification</th>
					<th scope="col">Video</th>
					<th scope="col">Duration</th>
					<th scope="col">Time</th>
				</tr>
			</thead>
			<tbody>
				@php $timezone = get_current_tz(); @endphp
				@foreach($notifications as $notification)
				@if($notification->time!='00:00' && $notification->time!='00:00:00')
				<tr>
					<th style="width: 20%;align-items: center;">
						<img class="contact-img" src="{{ URL(($notification->picture) ? $notification->picture : 'public/assets/img/john-doe.png') }}">
					</th>
					<td style="width:30%">{{ $notification->message }}</td>
					<td style="width:20%">
						@if($notification->video_link)
                        <video width="100%" height="auto" controls class="mx-auto">
                            <source src="{{ URL($notification->video_link) }}" type="video/mp4">
                        </video>
                        @endif 
					</td>
					<td style="width:15%">
						<img src="{{ URL('public/assets/img/clock.png') }}">&emsp;{{ $notification->time }}</td>
					<td style="width:15%">{{ my_time('dS F Y, h:i A',$notification->created_at,$timezone) }} </td>
				</tr>
				@endif
				@endforeach
			</tbody>
		</table>

		<nav class="mt-3" aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <li class="page-item {{ (empty($prev_page_url)) ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $prev_page_url }}">Previous</a>
                </li>
                
                @if(($current_page-1)<=$last_page && ($current_page-1)!=0)
                <li class="page-item"><a class="page-link" href="{{ $path }}?page={{$current_page-1}}&search={{ @$_GET['search'] }}">{{ $current_page-1 }}</a></li>
                @endif

                <li class="page-item active"><a class="page-link" href="">{{ $current_page }}</a></li>

                @if(($current_page+1)<=$last_page)
                <li class="page-item"><a class="page-link" href="{{ $path }}?page={{$current_page+1}}&search={{ @$_GET['search'] }}">{{ $current_page+1 }}</a></li>
                @endif

                <li class="page-item {{ ($current_page==$last_page) ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $next_page_url }}&search={{ @$_GET['search'] }}">Next</a>
                </li>
                <li class="page-item {{ ($current_page==$last_page) ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $last_page_url }}&search={{ @$_GET['search'] }}">Last</a>
                </li>
            </ul>
        </nav>
	</div>
	</div>
</section>
@endsection