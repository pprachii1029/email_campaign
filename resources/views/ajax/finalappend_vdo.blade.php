<tr>
    <th style="width:10%" scope="row">
        <input type="text" class="form-control order" name="order_video[]" style="border-radius: 5px !important;border: 1px solid gray !important;background-color: white;" onchange="change_order(this)" required value="{{ $order }}">
    </th>
    <th style="width:20%" scope="row">
        <input type="file" name="video[]" onchange="video_preview(this,'video_here_{{ $index }}')" content="video" required>
    </th>
    <td style="width:25%;" id="video_here_{{ $index }}">
        
    </td>
    <td style="width:25%">
        <div class="d-flex align-items-center ">
            <input type="text" class="form-control-small mr-3" id="validationCustom04" placeholder="Start" name="video_start[]" required>
            <input type="text" class="form-control-small mr-3" id="validationCustom04" placeholder="Duration" name="video_duration[]" required>Seconds
        </div>
    </td>
    <td style="width:20%; border:1px; color:white; solid !important">
        <div onclick="mute_unmute(this)" style="float: left;">
            <input type="hidden" name="mute[]" value="1" required>
            <img src="{{ URL('public/assets/img/mute.png') }}" class="" width="29px;"> 
        </div>
        &emsp;
        <i class="fa fa-trash delete" aria-hidden="true" onclick="delete_row(this)"></i>
    </td>
</tr>