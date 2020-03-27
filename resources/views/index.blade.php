@extends('layouts/app') 

@section('content')
<section id="content">
	<div class="container-fluid title-bar">
		<div class="row">
			<div class=" col-md-5 mt-2 d-flex">
				<div class="head">
					<h3 class="page-title pl-3">My Contacts</h3>
				</div>
				<div class="choose">
					<p>Choose List</p>
                </div>
                {{-- GROUPS --}}
				<div class="dropdown">
                    @if(count($groups)>0)
					<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="contact-img drop-img" src="{{ URL(($selected->picture) ? $selected->picture : 'public/assets/img/contact-img.png') }}">{{ $selected->group_name }}
                    </a>
					<div class="dropdown-menu hr" aria-labelledby="dropdownMenuLink">
                        @foreach($groups as $group)
						<a class="dropdown-item @if($group->id==$selected->id) active @endif" href="{{ route('home',['id'=>Crypt::encrypt($group->id)]) }}">
                            <img class="contact-img drop-img" src="{{ URL(($group->picture) ? $group->picture : 'public/assets/img/contact-img.png') }}">{{ $group->group_name }}
                        </a>
                        @endforeach
                    </div>
                    @else
                    <a class="btn btn-secondary dropdown-toggle" role="button">
                        No group found
                    </a>
                    @endif
                </div>
                {{-- GROUPS END --}}
			</div>
			<div class="col-md-7 d-flex justify-content-end" style="padding-right:40px">
				<div class="row">
                    @if(count($groups)>0)
					<a href="{{ route('add_contact') }}">
						<button class="btn btn-primary btn-lg add-contacts-btn mt-2"><i class="fa fa-plus" aria-hidden="true"></i>Add Contacts</button>
                    </a>
                    @endif
                    <a href="{{ route('add_group') }}">
                        <button type="button" class="btn btn-primary btn-lg add-contacts-btn mt-2"><i class="fa fa-plus" aria-hidden="true"></i>Add Groups</button>
                    </a>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-4">
		<div class="row">
			<div class="col-md-12 my-offset-md-1 right-side-section ">
				<div class="d-flex align-items-center justify-content-between">
					<h5 d-inline-block> {{ (@$selected->group_name) ? $selected->group_name : 'No group selected.' }}</h5>
					<form class="form-inline my-2 search-bar mt-2 ml-5 d-inline-block" method="GET">
                        <input type="hidden" name="id" value="{{ Crypt::encrypt(@$selected->id) }}">
                        <input class="form-control mr-sm-2 search-form-control see" name="search" type="search" placeholder="Search" aria-label="Search" value="{{ @$_GET['search'] }}">
						<img class="search-icon-img see" src="public/assets/img/search-icon.png">
					</form>
				</div>
				<div class="tab-content" id="v-pills-tabContent">
					<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
						<p>
							{{-- <p style="text-decoration: underline; cursor: pointer; display: inline-block; ">Select 50</p>or --}}
							<p style="text-decoration: underline; cursor: pointer; display: inline-block;" onclick="select_all()">Select All</p>
						</p>
					</div>
					<table class="table tab1 table-responsive">
						<thead>
							<tr>
								<th scope="col">Image</th>
								<th scope="col">Name</th>
								<th scope="col">Phone #</th>
								<th scope="col">Website</th>
								<th scope="col">Email</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
                            @if(isset($members['data']))
                            @foreach($members['data'] as $member)
							<tr>
								<th style="width: 5%;align-items: center;">
									<input class="form-check-input contact" type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
									<img class="contact-img" src="{{ ($member->picture) ? URL($member->picture) : URL('public/assets/img/john-doe.png') }}">
								</th>
								<td style="width:10%">{{ $member->first_name }} {{ $member->last_name }}</td>
								<td style="width:15%">{{ $member->phone_number }}</td>
								<td style="width:15%">{{ $member->website }}</td>
								<td style="width:15%">{{ $member->email }}</td>
								<td style="width:15%; border:1px; color:black; solid !important">
									<div class="action1">
										<div class="action1-inner" onclick="refresh(this)" style="cursor:pointer">
											<img src="public/assets/img/refresh-img.png">
											<p>Refresh</p>
										</div>
										<div class="action1-inner" onclick="play_audio(this)">
                                            <audio status='0' id="{{ @$id++ }}">
                                                <source src="{{ $member->audio ? URL($member->audio) : '' }}" type="audio/mpeg">
                                            </audio>
											<img src="public/assets/img/record-audio.png">
											<p>Audio</p>
										</div>
										<div class="btn-group dropleft">
                                            <i class="fas more-icon fa-ellipsis-h pr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                            
                                            <div class="dropdown-menu contact-dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('edit_contact',['contact_id'=>Crypt::encrypt($member->id)]) }}">Edit</a>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="delete_contact(this)" contact_id="{{ Crypt::encrypt($member->id) }}">Delete</a>
                                                <a class="dropdown-item" onclick="move_contact(this)" contact_id="{{ Crypt::encrypt($member->id) }}">Move</a>
                                                <a class="dropdown-item" onclick="subscribe_unsubscribe(this)" contact_id="{{ Crypt::encrypt($member->id) }}" event="{{ $member->unsubscribed ? 'Subscribe' : 'Unsubscribe' }}">{{ $member->unsubscribed ? 'Subscribe' : 'Unsubscribe' }}</a>
                                                <a class="dropdown-item" href="{{ route('view_contact',['id'=> Crypt::encrypt($member->id)]) }}">Profile</a>
                                                {{-- <a class="dropdown-item" href="profile-history.html">History</a> --}}
                                            </div>
                                        </div>
									</div>
								</td>
                            </tr>
                            @endforeach
                            @else
                            <td colspan="6">No data avaliable.</td>
                            @endif
						</tbody>
					</table>
				</div>
            </div>
            @if(count($members)>0)
			<nav class="mt-3" aria-label="Page navigation example">
				<ul class="pagination justify-content-end"> 
                    <li class="page-item {{ (empty($members['prev_page_url'])) ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $members['prev_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Previous</a>
                    </li>
                    
                    @if(($members['current_page']-1)<=$members['last_page'] && ($members['current_page']-1)!=0)
                    <li class="page-item"><a class="page-link" href="{{ $members['path'] }}?page={{$members['current_page']-1}}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">{{ $members['current_page']-1 }}</a></li>
                    @endif

                    <li class="page-item active"><a class="page-link" href="">{{ $members['current_page'] }}</a></li>

                    @if(($members['current_page']+1)<=$members['last_page'])
                    <li class="page-item"><a class="page-link" href="{{ $members['path'] }}?page={{$members['current_page']+1}}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">{{ $members['current_page']+1 }}</a></li>
                    @endif

                    <li class="page-item {{ ($members['current_page']==$members['last_page']) ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $members['next_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Next</a>
                    </li>
                    <li class="page-item {{ ($members['current_page']==$members['last_page']) ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $members['last_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Last</a>
                    </li>
				</ul>
            </nav>
            @endif
		</div>
    </div>
    <div id="recorder" style="display: none;"></div>
    <div id="msg_box" style="display: none;"></div>
</section>


<script>
    $(document).ready(function(){
        $('#m1').addClass('active1');
    });

    function play_audio(ele){
        audio = $(ele).children('audio');
        var sounds = document.getElementsByTagName('audio');
        
        for(i=0; i<sounds.length; i++){
            sounds[i].pause();
        }
        
        $('audio').each(function(){
            if($(this).attr('status')==1 && $(this).attr('id')!=$(audio).attr('id')){
                $(this).attr('status','0');
            }
        });

        if(audio.children('source').attr('src')!=''){
            if(audio.attr('status')=='0'){
                audio.trigger('play');
                audio.attr('status','1');
                success('<i class="fa fa-play-circle"></i> Playing...');
            }else{
                audio.trigger('pause');
                audio.attr('status','0');
                success('<i class="fa fa-stop-circle"></i> Stop');            }
        }else{
            error('No audio found');
        }
    }

    function refresh(ele){
        // $(ele).children('i').addClass('fa-spin');
        // setTimeout(function(){
            // $(ele).children('i').removeClass('fa-spin');
            location.reload();
        // },2000);
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
    
    function delete_contact(ele){
        id = $(ele).attr('contact_id');
        $.confirm({
            title: 'Confirm',
            content:'Do you want to delete this contact ?',
            buttons:{
                yes:{
                    text:'Yes',
                    action:function(){
                        $.ajax({
                            url: '{{ route("delete_contact") }}',
                            type:'POST',
                            data:{'id':id},
                            success: function(data){
                                $(ele).parent('div').parent('div').parent('div').parent('td').parent('tr').fadeOut(300);
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

    function select_all(ele){
        $('.contact').each(function(){
            obj = this;
            $(obj).prop('checked',true);
        });
    }

    function select_50(ele){
        i=0;
        $('.contact').each(function(){
            if(i<50){
                obj = this;
                $(obj).prop('checked',true);
            }else{
                return;
            }
            i++;
        });
    }
    function subscribe_unsubscribe(ele){
        id = $(ele).attr('contact_id');
        $.confirm({
            title: 'Confirm',
            content:'Do you want to '+$(ele).attr('event')+' this contact ?',
            buttons:{
                yes:{
                    text:'Yes',
                    action:function(){
                        hold_on();
                        $.ajax({
                            url: '{{ route("subscribe_unsubscribe") }}',
                            type:'POST',
                            data:{'id':id},
                            success: function(data){
                                hold_off();
                                //$(ele).parent('div').parent('div').parent('li').fadeOut(300);
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
</script>
@endsection