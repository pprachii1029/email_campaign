@extends('layouts.app')

@section('content')
<section id="content">
    <div class="container-fluid title-bar">
        <div class="row">
            <div class="col-md-4 mt-2">
                <h3 class="page-title pl-3">Unsubscribed List</h3>
            </div>
            <div class="col-md-8 d-flex justify-content-end">
                <form class="form-inline my-2 search-bar mt-2 ml-5">
                    {{-- <input class="form-control mr-sm-2 search-form-control " type="search" placeholder="Search" aria-label="Search">
                    <img class="search-icon-img" src="img/search-icon.png"> --}}
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4 main-side pb-5">
        <table class="table tab1 table-responsive" style="display: table;">
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
                @foreach($contacts as $contact)
                <tr>
                    <th style="width:15%" scope="row">
                        <img class="contact-img" src="{{ URL(($contact->picture) ? $contact->picture : 'public/assets/img/john-doe.png') }}">
                    </th>
                    <td style="width:15%;">{{ $contact->first_name }} {{ $contact->last_name }}</td>
                    <td style="width:20%;font-size: 16px;">{{ $contact->phone_number }}</td>
                    <td style="width:15%">{{ $contact->website }}</td>
                    <td style="width:20%">{{ $contact->email }}</td>
                    <td style="width:20%; border:1px; color:white; solid !important">
                        <a href="{{ route('edit_contact',['contact_id'=>Crypt::encrypt($contact->id)]) }}"><i class="fas fa-pencil-alt delete"></i>  </a>
                        {{-- <i class="fa fa-trash delete" onclick="delete_contact(this)" contact_id="{{ Crypt::encrypt($contact->id) }}"></i> --}}
                        <i class="fa fa-trash delete" onclick="subscribe_unsubscribe(this)" contact_id="{{ Crypt::encrypt($contact->id) }}"></i>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

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

    function subscribe_unsubscribe(ele){
        id = $(ele).attr('contact_id');
        $.confirm({
            title: 'Confirm',
            content:'Do you want to subscribe this contact ?',
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
                                $(ele).parent('td').parent('tr').fadeOut(300);
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
    }   
</script>
@endsection