<div class="list-group">
    @foreach($groups as $group)
    <button type="button" class="list-group-item list-group-item-action" onclick="change_group(this)" group_id="{{ Crypt::encrypt($group->id) }}" contact_id="{{ $contact_id }}">
        {{ $group->group_name }}
    </button>
    @endforeach
</div>

<script>
    function change_group(ele){
        $.confirm({
            title: 'Confirm',
            content:'Are you sure?',
            buttons:{
                yes:{
                    text:'Yes',
                    action: function(){
                        hold_on();
                        group_id    = $(ele).attr('group_id');
                        contact_id  = $(ele).attr('contact_id');
                        $.ajax({
                            url: "{{ route('change_group') }}",
                            type:'post',
                            data:{'group_id':group_id,'contact_id':contact_id},
                            success: function(data){
                                if(data==200){
                                    location.reload();
                                }else{
                                    $.alert({
                                        title:'Error',
                                        content:'Internal server error.'
                                    });
                                }
                                hold_off();
                            }
                        });
                    }
                },
                no:{
                    text:'No',
                    action:function(){
                        
                    }
                }
            }
        })
    }
</script>