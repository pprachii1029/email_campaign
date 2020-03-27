@extends('layouts.app')

@section('content')
    <section id="content">
        <div class="container-fluid title-bar ">
            <div class="row">
                <div class="col-md-4 mt-2">
                    <h3 class="page-title pl-3">My Campaigns</h3>
                </div>
                <div class="col-md-8 d-flex justify-content-end">
                    <div class="row my-row">
			<div class="col-md-6">
                        <div class="compose-campaign.html">
                            <button type="button" class="btn btn-info btn-primary btn-lg add-contacts-btn mt-2 navBtn_prv" data-toggle="collapse" data-target="#demo"><i class="fa fa-plus" aria-hidden="true"></i> Compose Campaign</button>
                              <div id="demo" class="collapse" style="padding-left: 34px;">
                                <ul class="composer_subText" style="list-style: none; border-radius: 19px;  color: #000; padding:0px;">
                                    <li style="padding: 9px 12px 9px 13px;"> <a href="{{ route('create_campaign') }}">Email compaign</a></li>
                                    <li style="padding: 9px 12px 9px 13px;"> <a href="{{ route('email_drip') }}">Email Drip</a></li>
                                </ul>
                              </div>
                        </div>
			</div>
			<div class="col-md-6">
                        <form class="form-inline my-2 search-bar mt-2 ml-2">
                            <input class="form-control mr-sm-2 search-form-control " type="search" placeholder="Search" aria-label="Search">
                            <img class="search-icon-img" src="public/assets/img/search-icon.png">
                        </form>
			</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-4 main-side pb-5 send_campaignTemp">
            <div class="tabs">
              <ul id="tabs-nav">
                <li><a href="#tab1">Video Email Campaigns</a></li>
                <li><a href="#tab2">Automation Email Campaigns</a></li>
              </ul> <!-- END tabs-nav -->
              <div id="tabs-content">
                <div id="tab1" class="tab-content">
                  <table class="table tab1 table-responsive nw">
                        <thead>
                            <tr>
                                <th scope="col">Video</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Date</th>
                                <th scope="col">Sent To</th>
                                <th scope="col">Sent Users</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $campaign)
                            <tr>
                                <th style="width:15%" scope="row">
                                    @if($campaign->final_video)
                                    <video poster="" width="100%" height="20%" controls="controls">
                                        <source src="{{ URL($campaign->final_video) }}" type="video/mp4" />
                                    </video>
                                    @else
                                    <img src="{{ URL('public/assets/img/seo-5.png') }}" class="img-fluid">
                                    @endif
                                </th>
                                <td style="width:12%;">{{ $campaign->title }}</td>
                                <td style="width:15%;font-size: 12px;">{{ $campaign->message }}</td>
                                <td style="width:15%">{{ my_time('d M,Y h:i A',$campaign->created_at,'Asia/Kolkata') }}</td>
                                <td style="width:10%">{{ $campaign->group_name }}</td>
                                <td style="width:21">({{ $campaign->sent_users  }}) {{ $campaign->total_users }}</td>
                                <td style="width:20%; border:1px; color:white; solid !important">
                                    {{-- <i class="fas fa-pencil-alt delete"></i>   --}}
                                    <i class="fa fa-trash delete" onclick="delete_camp(this)" id="{{ Crypt::encrypt($campaign->id) }}"></i>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <nav class="mt-3" aria-label="Page navigation example">
                        <ul class="pagination justify-content-end"> 
                            <li class="page-item {{ (empty($pages['prev_page_url'])) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pages['prev_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Previous</a>
                            </li>
                            
                            @if(($pages['current_page']-1)<=$pages['last_page'] && ($pages['current_page']-1)!=0)
                            <li class="page-item"><a class="page-link" href="{{ $pages['path'] }}?page={{$pages['current_page']-1}}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">{{ $pages['current_page']-1 }}</a></li>
                            @endif

                            <li class="page-item active"><a class="page-link" href="">{{ $pages['current_page'] }}</a></li>

                            @if(($pages['current_page']+1)<=$pages['last_page'])
                            <li class="page-item"><a class="page-link" href="{{ $pages['path'] }}?page={{$pages['current_page']+1}}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">{{ $pages['current_page']+1 }}</a></li>
                            @endif

                            <li class="page-item {{ ($pages['current_page']==$pages['last_page']) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pages['next_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Next</a>
                            </li>
                            <li class="page-item {{ ($pages['current_page']==$pages['last_page']) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pages['last_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Last</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div id="tab2" class="tab-content">
                  <table class="table tab1 table-responsive nw">
                        <thead>
                            <tr>
                                <th scope="col">Email drips</th>
                                <th scope="col">Staus</th>
                                <th scope="col">Drip Created at</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                            <tr>
                                <th style="width:15%" scope="row">
                                    {{ $group->automation_name }}
                                </th>
                                <th style="width:15%" scope="row">
                                    @if($group->status == 0 )
                                        <p class="emailAutomationStatus statusBtn pause_btn"><i class="fas fa-toggle-off" onclick="start_camp(this)" id="{{ Crypt::encrypt($group->id) }}"> </i></p>
                                    @elseif($group->status == 1)
                                        <p class="emailAutomationStatus statusBtn play_btn"><i class="fas fa-toggle-on" style="color:green;" onclick="pause_camp(this)" id="{{ Crypt::encrypt($group->id) }}"></i></p>
                                    @else
                                        <p class="emailAutomationStatus statusBtn "><i style="color: green;" class="fa fa-check-circle" aria-hidden="true"></i></p>
                                    @endif 

                                    
                                </th>
                                <td style="width:12%;">{{ my_time('d M,Y h:i A',$group->created_at,'Asia/Kolkata') }}</td>
                                <td style="width:15%; border:1px; color:white; " data_template="21">
                                    <a href="/edit_email_drip?template={{ Crypt::encrypt($group->id) }}"><i class="fas fa-pencil-alt delete" aria-hidden="true"></i></a>
                                    &emsp; 
                                    <i class="fa fa-trash delete" onclick="delete_automation_camp(this)" id="{{ Crypt::encrypt($group->id) }}"></i>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                    @if( $groups_pages['total'] >=2)
                    <nav class="mt-3" aria-label="Page navigation example">
                        <ul class="pagination justify-content-end"> 
                            <li class="page-item {{ (empty($pages['prev_page_url'])) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pages['prev_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Previous</a>
                            </li>
                            
                            @if(($groups_pages['current_page']-1)<=$groups_pages['last_page'] && ($groups_pages['current_page']-1)!=0)
                            <li class="page-item"><a class="page-link" href="{{ $pages['path'] }}?page={{$pages['current_page']-1}}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">{{ $groups_pages['current_page']-1 }}</a></li>
                            @endif

                            <li class="page-item active"><a class="page-link" href="">{{ $groups_pages['current_page'] }}</a></li>

                            @if(($groups_pages['current_page']+1)<=$groups_pages['last_page'])
                            <li class="page-item"><a class="page-link" href="{{ $pages['path'] }}?page={{$pages['current_page']+1}}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">{{ $groups_pages['current_page']+1 }}</a></li>
                            @endif

                            <li class="page-item {{ ($pages['current_page']==$pages['last_page']) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pages['next_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Next</a>
                            </li>
                            <li class="page-item {{ ($pages['current_page']==$pages['last_page']) ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $pages['last_page_url'] }}&search={{ @$_GET['search'] }}&id={{ @$_GET['id'] }}">Last</a>
                            </li>
                        </ul>
                    </nav>
                    @endif
                </div>

              </div> <!-- END tabs-content -->
            </div> <!-- END tabs -->
            
        </div>
        
    </section>
    <script>
        $(document).ready(function(){
            $('#m3').addClass('active1');
        });

        function delete_camp(ele){
            id = $(ele).attr('id');
            $.alert({
                title:'Confirm',
                content:"Are you sure?",
                buttons:{
                    yes:{
                        text:"YES",
                        action:function(){
                            $.ajax({
                                url: '{{ route("delete_camp") }}',
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
        function start_camp(ele){
            id = $(ele).attr('id');
            $.alert({
                title:'Confirm',
                content:"Are you sure?",
                buttons:{
                    yes:{
                        text:"YES",
                        action:function(){
                            $.ajax({
                                url: '{{ route("start_automation") }}', //start_automation
                                type:'POST',
                                data:{'id':id},
                                success:function(data){
                                    console.log(data);
                                    if(data==200){
                                        window.location.replace('/sent_campaigns#tab2');
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
        function pause_camp(ele){
            id              = $(ele).attr('id');
            $.confirm({
                title: 'Confirm',
                content:'Do you want to stop the campaign?',
                buttons:{
                    yes:{
                        text:'Yes',
                        action:function(){
                            hold_on();
                            $.ajax({
                                url: '{{ route("pause_automation") }}',
                                type:'post',
                                data:{'id':id},
                                success:function(data){
                                    hold_off();
                                    window.location.replace('/sent_campaigns#tab2');
                                },
                                error: function(data){
                                    alert('something went wrong.');
                                    hold_off();
                                }
                            });
                        }
                    },
                    no:{
                        text:'No'
                    }
                }
            });
        }//eo
        function delete_automation_camp(ele){
            id = $(ele).attr('id');
            $.alert({
                title:'Confirm',
                content:"Are you sure?",
                buttons:{
                    yes:{
                        text:"YES",
                        action:function(){
                            $.ajax({
                                url: '{{ route("delete_whole_automation") }}',
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
        // jQuery(window).on('load',function(){
        //     jQuery(document).on('click','.navBtn_prv',function(){
        //     if( jQuery('.navBtn_prv').hasClass('collapsed')){
        //         $('.navBtn_prv').find('i').removeClass('fa-plus');
        //         $('.navBtn_prv').find('i').addClass('fa-minus');
        //     }else{
        //         $('.navBtn_prv').find('i').removeClass('fa-minus');
        //         $('.navBtn_prv').find('i').addClass('fa-plus');
                
        //     }
        // });

        // });
        // Show the first tab and hide the rest
        $('#tabs-nav li:first-child').addClass('active');
        $('.tab-content').hide();
        $('.tab-content:first').show();

        // Click function
        $('#tabs-nav li').click(function(){
          $('#tabs-nav li').removeClass('active');
          $(this).addClass('active');
          $('.tab-content').hide();
          
          var activeTab = $(this).find('a').attr('href');
          $(activeTab).fadeIn();
          return false;
        });
        jQuery(document).ready(function(){
            var url = document.location.toString(); //alert(url);
            if (url.match('#')) {
                //alert('alalla');
                jQuery('ul#tabs-nav li').removeClass('active');
                jQuery('div#tabs-content .tab-content').css('display','none');
                jQuery('ul#tabs-nav a[href="#' + url.split('#')[1] + '"]').parent().addClass('active');
                jQuery('div#tabs-content #' + url.split('#')[1] ).css('display','block');
            }

            jQuery('')
        });
    </script>
    <style type="text/css">
        .pause_btn i {color: orange; }
        .play_btn i {color: green;  }
    </style>
@endsection