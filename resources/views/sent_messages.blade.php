@extends('layouts.app')

@section('content')

<section id="content">
    <div class="container-fluid title-bar">
        <div class="row">
            <div class="col-md-4 mt-2">
                <h3 class="page-title pl-3">Sent Messages</h3>
            </div>
            <div class="col-md-8 d-flex justify-content-end">
                <div class="row my-row">
                    <a href="{{ route('compose_message') }}">
                        <a href="{{ route('compose_message') }}" class="btn btn-primary btn-lg add-contacts-btn mt-2 ml-5"><i class="fa fa-plus" aria-hidden="true"></i> Compose Message</a>
                    </a>
                    
                    <form class="form-inline my-2 search-bar mt-2 ml-5" method="GET">
                        <input class="form-control mr-sm-2 search-form-control" name="search" type="search" placeholder="Search" aria-label="Search" value="{{ @$_GET['search'] }}">
                        <img class="search-icon-img" src="public/assets/img/search-icon.png">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4 main-side pb-5">
        <table class="table tab1 table-responsive">
            <thead>
                <tr>
                    <th scope="col">User</th>
                    <th scope="col">Name</th>
                    <th scope="col">Video</th>
                    <th scope="col">Title</th>
                    <th scope="col">Date</th>
                    {{-- <th scope="col">Sent By</th> --}}
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            @if(count($message)>0)
                @foreach($message as $row)
                <tr>
                    <th style="width: 10%;align-items: center;">
                        <img class="contact-img" src="{{ URL(($row->picture) ? $row->picture : 'public/assets/img/john-doe.png') }}">
                    </th>
                    <td style="width:15%">{{ $row->first_name }} {{ $row->last_name }}</td>
                    <th style="width:15%" scope="row">
                        @if($row->final_video)
                        <video width="100%" height="auto" controls class="mx-auto">
                            <source src="{{ URL($row->final_video) }}" type="video/mp4">
                        </video>
                        @else 
                        <img src="{{ URL('public/assets/img/seo-5.png') }}" class="img-fluid mx-auto">
                        @endif
                    </th>
                    <td style="width:15%;">{{ $row->title }}</td>
                    <td style="width:21%;font-size: 16px;">{{ my_time('dS F Y, h:i A',$row->created_at,'Asia/Kolkata') }}</td>
                    {{-- <td style="width:10%">
                        <img src="img/linkedin-icon.png" class="img-fluid">
                    </td> --}}
                    <td style="width:15%; border:1px; color:white; solid !important">
                        {{-- <i class="fas fa-pencil-alt delete"></i>   --}}
                        <i class="fa fa-trash delete" onclick="delete_message(this)" id="{{ Crypt::encrypt($row->id) }}"></i>
                    </td>
                </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <nav class="mt-3" aria-label="Page navigation example">
        <ul class="pagination justify-content-end"> 
            <li class="page-item {{ (empty($pages['prev_page_url'])) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $pages['prev_page_url'] }}&search={{ @$_GET['search'] }}">Previous</a>
            </li>
            
            @if(($pages['current_page']-1)<=$pages['last_page'] && ($pages['current_page']-1)!=0)
            <li class="page-item"><a class="page-link" href="{{ $pages['path'] }}?page={{$pages['current_page']-1}}&search={{ @$_GET['search'] }}">{{ $pages['current_page']-1 }}</a></li>
            @endif

            <li class="page-item active"><a class="page-link" href="">{{ $pages['current_page'] }}</a></li>

            @if(($pages['current_page']+1)<=$pages['last_page'])
            <li class="page-item"><a class="page-link" href="{{ $pages['path'] }}?page={{$pages['current_page']+1}}&search={{ @$_GET['search'] }}">{{ $pages['current_page']+1 }}</a></li>
            @endif

            <li class="page-item {{ ($pages['current_page']==$pages['last_page']) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $pages['next_page_url'] }}&search={{ @$_GET['search'] }}">Next</a>
            </li>
            <li class="page-item {{ ($pages['current_page']==$pages['last_page']) ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $pages['last_page_url'] }}&search={{ @$_GET['search'] }}">Last</a>
            </li>
        </ul>
    </nav>
</section>

<script>
    $(document).ready(function(){
        $('#m2').addClass('active1');
    });

    function delete_message(ele){
        id = $(ele).attr('id');
        $.alert({
            title:'Confirm',
            content:"Are you sure?",
            buttons:{
                yes:{
                    text:"YES",
                    action:function(){
                        $.ajax({
                            url: '{{ route("delete_message") }}',
                            type:'POST',
                            data:{'id':id},
                            success:function(data){
                                if(data==200){
                                    $(ele).parent('td').parent('tr').hide(300);
                                }else{
                                    $.alert({
                                        title:'Error',
                                        content:"Internal server error."
                                    })
                                }
                            }
                        });
                    }
                },
                No:{
                    text:"No",
                    action:function(){
                        
                    }
                }
            }
        })
    }
</script>
@endsection