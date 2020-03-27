@extends('layouts.app') 

@section('content')
<section id="content">
    <div class="container-fluid title-bar">
        <div class="row">
            <div class=" col-md-5 mt-2 d-flex">
                <div class="head">
                    <h3 class="page-title pl-3">Compose Message</h3>
                </div>
                <div class="choose">
                    <p>Choose List</p>
                </div>
                {{-- GROUPS --}}
                <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="contact-img drop-img" src="{{ URL(($selected->picture) ? $selected->picture : 'public/assets/img/contact-img.png') }}">{{ $selected->group_name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        @foreach($groups as $group)
                        <a class="dropdown-item @if($group->id==$selected->id) active @endif" href="{{ route('compose_message',['id'=>Crypt::encrypt($group->id)]) }}">
                            <img class="contact-img drop-img" src="{{ URL(($group->picture) ? $group->picture : 'public/assets/img/contact-img.png') }}">{{ $group->group_name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                {{-- GROUPS END --}}
            </div>
            
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12 my-offset-md-1 right-side-section ">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 d-inline-block> {{ @$selected->group_name }}</h5>
                    <form class="form-inline my-2 search-bar mt-2 ml-5 d-inline-block" method="GET">
                        <input type="hidden" name="id" value="{{ Crypt::encrypt($selected->id) }}">
                        <input class="form-control mr-sm-2 search-form-control" name="search" type="search" placeholder="Search" aria-label="Search" value="{{ @$_GET['search'] }}">
                        <img class="search-icon-img" src="public/assets/img/search-icon.png">
                    </form>
                </div>
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
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
                            @foreach($members['data'] as $member)
                            <tr>
                                <th style="width: 5%;align-items: center;">
                                    <img class="contact-img" src="{{ ($member->picture) ? URL($member->picture) : URL('public/assets/img/john-doe.png') }}">
                                </th>
                                <td style="width:10%">{{ $member->first_name }} {{ $member->last_name }}</td>
                                <td style="width:15%">{{ $member->phone_number }}</td>
                                <td style="width:15%">{{ $member->website }}</td>
                                <td style="width:15%">{{ $member->email }}</td>
                                <td style="width:15%; border:1px;  color:black; solid !important"> 
                                    <div class="action1">
                                        <div class="action1-inner" onclick="send_message(this)" contact_id="{{ Crypt::encrypt($member->id) }}">                              
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            <p>Send Message</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
        </div>
    </div>
    
</section>


<script>
    $(document).ready(function(){
        $('#m2').addClass('active1');
    });

    function get_template_detail(id){
        hold_on();
        $.ajax({
            url: '{{ route("get_template_detail") }}',
            type:'post',
            data:{'id':id},
            success: function(data){
                hold_off();
                data  = JSON.parse(data);
                if(data){
                    $('#subject').val(data.title);
                    $('#description').val(data.description);
                }else{
                    $('#subject').val('');
                    $('#description').val('');
                }
            }
        });
    }

    function send_message(ele){
        contact_id = $(ele).attr('contact_id');
        $.alert({
            columnClass: 'col-md-6 col-md-offset-3',
            title:"Send Email",
            content: function(){
                pop = this;
                $.ajax({
                    url: "{{ route('send_message_pop') }}",
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