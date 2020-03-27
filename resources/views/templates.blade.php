@extends('layouts.app')

@section('content')
<section id="content">
        <div class="container-fluid title-bar">
            <div class="row">
                <div class="col-md-3 mt-2">
                    <h3 class="page-title pl-3">My Templates</h3>
                </div>
                <div class="col-md-9 d-flex justify-content-end">
                    <div class="row my-row">
                        <!-- <a href="">
                            <button type="button" class="btn btn-primary btn-lg add-contacts-btn mt-2">
                                <img class="pr-2"><i class="fas fa-download"></i> Download CSV</button>
                        </a> -->
                        <a href="{{ route('add_template') }}">
                            <button type="button" class="btn btn-primary btn-lg add-contacts-btn mt-2 ml-5">
                                <img class="pr-2"><i class="fas fa-plus"></i> Add Template</button>
                        </a>
                        <form class="form-inline my-2 search-bar mt-2" method="GET">
                            <input class="form-control mr-sm-2 search-form-control " type="search" placeholder="Search" aria-label="Search" name="search" value="{{ @$_GET['search'] }}" style="padding-left: 20px !important;">
                            <img class="search-icon-img" src="{{ URL('public/assets/img/search-icon.png') }}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4 main-side pb-5">
            <table class="table tab1 table-responsive">
                <thead>
                    <tr>
                        <th scope="col">Video</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Created On</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($data)>0)
                        @php $timezone = get_current_tz(); @endphp
                        @foreach($data as $template)
                        <tr>
                            <th style="width:25%" scope="row">
                                @if($template['final_video'])
                                <video width="100%" height="auto" controls class="mx-auto">
                                    <source src="{{ URL($template['final_video']) }}" type="video/mp4">
                                </video>
                                @else 
                                <img src="{{ URL('public/assets/img/seo-5.png') }}" class="img-fluid mx-auto">
                                @endif
                            </th>
                            <td style="width:20%;">{{ $template['title'] }}</td>
                            <td style="width:20%;font-size: 12px;">{{ substr($template['description'],0,200) }}...</td>
                            <td style="width:20%">{{ my_time('dS F Y, h:i A',$template['created_at'],$timezone) }}</td>
                            <td style="width:15%; border:1px; color:white; solid !important">
                                <a href="{{ route('arrange_template_content',['template_id'=>Crypt::encrypt($template['id'])]) }}"><i class="fas fa-pencil-alt delete"></i></a>
                                &emsp; 
                                <i class="fa fa-trash delete" onclick="delete_template(this);" id="{{ Crypt::encrypt($template['id']) }}"></i>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <span class="text-center">No template found.</span>
                    @endif
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
    </section>
    <script>
        function delete_template(ele){
            id = $(ele).attr("id");
            $.confirm({
                title: 'Confirm',
                content:"Are you sure ?",
                buttons:{
                    yes:{
                        text: 'Yes',
                        action:function(){
                            $.ajax({
                                url: '{{ route("delete_template") }}',
                                type:'POST',
                                data:{'id':id},
                                success:function(data){
                                    if(data==200){
                                        $(ele).parent('td').parent('tr').hide(500);
                                    }else if(data==400){
                                        alert("You are not allow to delete it because you using this emplate in email drip.");
                                    }else{
                                        $.alert({
                                            title:'Error',
                                            content:"Facing some error. Please try after some time."
                                        })
                                    }
                                }
                            })
                        }
                    },
                    no:{
                        text:'No'
                    }
                }
            })
        }
        $(function(){
            $('#m4').addClass('active1');
        })
    </script>    
@endsection