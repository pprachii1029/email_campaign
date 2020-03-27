<tr>
    <th style="width:10%" scope="row">
        <input type="text" class="form-control order" name="order_url[]" style="border-radius: 5px !important;border: 1px solid gray !important;background-color: white;" onchange="change_order(this)" required value="{{ $order }}">
    </th>
    <th style="width:20%" scope="row">
        <input type="url" class="form-control" id="url_input__{{ $index }}" onblur="capture_url('url_input__{{ $index }}','url_preview_{{ $index }}','url_ss_{{ $index }}')" style="border-radius: 5px !important;border: 1px solid gray !important;background-color: white;">
    </th>
    <td style="width:25%;">
        <img src="" class="pl-3" id="url_preview_{{ $index }}" width="200px">
    </td>
    <td style="width:25%">
        <div class="d-flex align-items-center ">
            <input type="text" class="form-control form-control-small mr-3" id="validationCustom04" name="url_duration[]" required value="5">Seconds
            <input type="hidden" name="url_ss[]" value="" id="url_ss_{{ $index }}" required>
        </div>
    </td>
    <td style="width:20%; border:1px; color:white; solid !important"> 
        <i class="fa fa-trash delete" aria-hidden="true" onclick="delete_row(this)"></i>
    </td>
</tr>