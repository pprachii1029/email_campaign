<div class="d-flex align-items-center justify-content-between">
    <h5 d-inline-block> {{ $group_name }}</h5>
    <form class="form-inline my-2 search-bar mt-2  ml-5 d-inline-block">
        <input class="form-control mr-sm-2 search-form-control " type="search" placeholder="Search" aria-label="Search">
        <img class="search-icon-img" src="{{ URL('public/assets/img/search-icon.png') }}">
    </form>
</div>

<div class="tab-content" id="v-pills-tabContent">
    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
        <p>
            <p style="text-decoration: underline; cursor: pointer; display: inline-block;" onclick="select_50(this)">Select 50 </p> or
            <p style="text-decoration: underline; cursor: pointer; display: inline-block;" onclick="select_all(this)">Select All</p>
        </p>
        <ul class="contacts-ul">
        @if($contacts)
            @foreach($contacts as $contact)
            <li>
                <div class="form-check form-check1 pl-1 ml-4 ml-md-0">
                    <input class="form-check-input position-static contact" type="checkbox" id="blankCheckbox" email="{{ $contact->email }}" name="{{ $contact->first_name }} {{ $contact->last_name }}" contact_id="{{ Crypt::encrypt($contact->id) }}">
                </div>
                <img class="contact-img" src="{{ URL(($contact->picture) ? $contact->picture : 'public/assets/img/john-doe.png') }}">
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <h5>{{ $contact->first_name }} {{ $contact->last_name }}</h5>
                    <h6><i class="fas fa-phone-alt pr-2"></i> {{ $contact->phone_number }}</h6>
                </div>
                <div class="d-flex flex-column align-items-start justify-content-center">
                    <h6><img src="{{ URL('public/assets/img/globe.png') }}"> {{ $contact->website }}</h6>
                    <h6><i class="far fa-envelope"></i>{{ $contact->email }}</h6>
                </div>

                <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ URL('public/assets/img/refresh-img.png') }}">
                    <h6>Refresh</h6>
                </div>
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ URL('public/assets/img/record-audio.png') }}">
                    <h6>Record Audio</h6>
                </div>

                <div class="btn-group dropleft">
                    <i class="fas more-icon fa-ellipsis-h pr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                    
                    <div class="dropdown-menu contact-dropdown-menu">
                        <a class="dropdown-item" href="{{ route('edit_contact',['contact_id'=>Crypt::encrypt($contact->id)]) }}">Edit</a>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="delete_contact(this)" contact_id="{{ Crypt::encrypt($contact->id) }}">Delete</a>
                        <a class="dropdown-item" href="javascript;" onclick="move_contact()" contact_id="{{ Crypt::encrypt($contact->id) }}">Move</a>
                        <a class="dropdown-item" onclick="subscribe_unsubscribe(this)" contact_id="{{ Crypt::encrypt($contact->id) }}" event="{{ $contact->unsubscribed ? 'Subscribe' : 'Unsubscribe' }}">{{ $contact->unsubscribed ? 'Subscribe' : 'Unsubscribe' }}</a>
                        <a class="dropdown-item" href="{{ route('view_contact',['id'=> Crypt::encrypt($contact->id)]) }}">Profile</a>
                        <a class="dropdown-item" href="profile-history.html">History</a>
                    </div>
                </div>
            </li>
            @endforeach
        @else
            <span class="text-center">No contacts in this group.</span>
        @endif
        </ul>

    </div>
    
</div>

<script>
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
                                $(ele).parent('div').parent('div').parent('li').fadeOut(300);
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
                        $.ajax({
                            url: '{{ route("subscribe_unsubscribe") }}',
                            type:'POST',
                            data:{'id':id},
                            success: function(data){
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