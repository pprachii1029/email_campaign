@extends('layouts.app') 

@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<style>
    .draggable{
        height: 150px !important;
        padding: 5px 5px !important;
    }
    .draggable img{
        height: 150px !important;
        width: 100% !important;
    }
    .droppable{
        background-color: #fff;
    }
</style>
<section id="content">
    <div class="container-fluid title-bar">
        <div class="row">
            <div class="col-md-4 mt-2">
                <h3 class="page-title pl-3">Arrange Template</h3>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="row droppable"  id="video_view">
            @foreach($template as $key => $row)

            <div class="col-md-3 draggable" content="{{ $row['content'] }}" id="{{ $key }}">
                @if($row['content']=='video')
                <video width="100%" controls class="myvideo" style="height:100%">
                    <source src="{{ URL($row['video']) }}" >
                </video>
                @elseif($row['content']=='photo')
                <img src="{{ URL($row['photo']) }}" class="pl-3">
                @elseif($row['content']=='url')
                <img src="{{ URL($row['url']) }}" class="pl-3">
                @elseif($row['content']=='snapshot')
                <img src="{{ URL($row['snapshot_ss']) }}" class="pl-3">
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <div class="container-fluid mt-4">
        <div class="row droppable"  id="video_view">
            <div class="col-md-9 pt-4 pl-5 pb-5">
                <form method="POST" action="{{ route('make_video') }}" onsubmit="hold_on()">
                    <input type="hidden" name="new_pos" id="new_pos" value="">
                    <input type="hidden" name="template_id" id="template_id" value="{{ $template_id }}">
                    <input type="hidden" name="pre_pos" id="pre_pos" value="{{ json_encode($template) }}">
                    
                    <div class="row d-flex justify-content-center mt-5">
                        <button type="submit" name="submit" value="save" class="btn preview-btn ">Save</button>
                        <button type="submit" name="submit" value="preview" class="btn preview-btn ml-5">Preview</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>

<script>
    $(document).ready(function() {
        $("#video_view").sortable({
            update: function(event, ui) {
                var changedList = this.id;
                var order   = $(this).sortable('toArray');
                var new_pos = order.join(',');
                $('#new_pos').val(new_pos);
            }
        });
        $( ".draggable" ).disableSelection();            
    });
</script>
@endsection