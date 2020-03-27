@extends('layouts.app')

@section('content')
    <style>
        .harman{
            border-radius: 5px !important;
            border: 1px solid gray !important;
            background-color: white;
        }
        .list-group{
            cursor: pointer;
        }
    </style>
    <section id="content">
        <div class="container-fluid title-bar">
            <div class="row">
                <div class="col-md-4 mt-2">
                    <h3 class="page-title pl-3">Compose Campaign</h3></div>
                <div class="col-md-8 d-flex justify-content-end"></div>
            </div>
        </div>

        <div class="container-fluid main-side mt-4 ">
            <div class="container">
                <div class="row pt-4 pb-5">
                    <div class="col-md-12">
                        <form class="" method="POST" action="{{ route('send_email_in_group') }}" onsubmit="hold_on()">
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Email Subject</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control harman" id="subject" value="" name="subject">
                                    <small class="text-muted" style="float: right;">&emsp;Press (#) to insert dynmic tags.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Email Message</label>
                                <div class="col-sm-6">
                                    <textarea class="form-control harman" id="description" rows="3" name="message"></textarea>
                                    <small class="text-muted" style="float: right;">&emsp;Press (#) to insert dynmic tags.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Choose Contacts</label>
                                <div class="col-sm-6">
                                    <select id="inputState" class="form-control harman" required name="group_id">
                                        @foreach($groups as $group)
                                        <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Choose Template</label>
                                <div class="col-sm-6">
                                    <select id="inputState" class="form-control" name="template_id" onchange="get_template_detail(this.value)">
                                        <option value="0">Select</option>
                                        @foreach($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center mt-5">
                                <button type="submit" class="btn preview-btn " value="preview">Preview</button>
                                <button type="submit" class="btn preview-btn ml-5" value="send">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#subject').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==35){
                    $.dialog({
                        title: 'Select input tag',
                        animation: 'RotateX',
                        keyboardEnabled: true,
                        content:'<ul class="list-group"><li class="list-group-item" onclick="add_tag(\'firstName\');">First Name</li><li class="list-group-item" onclick="add_tag(\'lastName\')">Last Name</li><li class="list-group-item" onclick="add_tag(\'email\')">Email</li><li class="list-group-item" onclick="add_tag(\'designation\')">Designation</li><li class="list-group-item" onclick="add_tag(\'phoneNumber\')">Phone Number</li><li class="list-group-item" onclick="add_tag(\'website\')">Website</li><li class="list-group-item" onclick="add_tag(\'facebook\')">Facebook</li><li class="list-group-item" onclick="add_tag(\'linkedin\')">Linkedin</li></ul>',
                    });
                }
            });

            $('#description').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode==35){
                    $.dialog({
                        title: 'Select input tag',
                        animation: 'RotateX',
                        keyboardEnabled: true,
                        content:'<ul class="list-group"><li class="list-group-item" onclick="add_tag_desc(\'firstName\');">First Name</li><li class="list-group-item" onclick="add_tag_desc(\'lastName\')">Last Name</li><li class="list-group-item" onclick="add_tag_desc(\'email\')">Email</li><li class="list-group-item" onclick="add_tag_desc(\'designation\')">Designation</li><li class="list-group-item" onclick="add_tag_desc(\'phoneNumber\')">Phone Number</li><li class="list-group-item" onclick="add_tag_desc(\'website\')">Website</li><li class="list-group-item" onclick="add_tag_desc(\'facebook\')">Facebook</li><li class="list-group-item" onclick="add_tag_desc(\'linkedin\')">Linkedin</li></ul>',
                    });
                }
            });               
        });

        function add_tag(val){
            str = $('#subject').val();
            $('#subject').val(str+val);
            $('.jconfirm-closeIcon').trigger("click");
        }

        function add_tag_desc(val){
            str = $('#description').val();
            $('#description').val(str+val);
            $('.jconfirm-closeIcon').trigger("click");
        }
    </script>
    <script>
        $(document).ready(function(){
            $("#m3").addClass('active1');
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
    </script>
@endsection