<tr>
    <th style="width:10%" scope="row">
        <input type="text" class="form-control order" name="order_photo[]" style="border-radius: 5px !important;border: 1px solid gray !important;background-color: white;"  required value="{{ $order }}">
    </th>
    <td style="width:20%" scope="row">
        <input type="file" name="photo[]" content="photo" onchange="picture_preview(this,'photo_view_{{ $index }}')" required/>
    </td>
    <td style="width:25%;" id="photo_view_{{ $index }}">
        
    </td>
    <td style="width:25%">
        <div class="d-flex align-items-center ">
            <input type="text" class="form-control form-control-small mr-3" id="validationCustom04" name="photo_duration[]" value="5" required>Seconds
        </div>
    </td>
    <td style="width:20%; border:1px; color:white; solid !important"> 
        <i class="fa fa-trash delete" aria-hidden="true" onclick="delete_row(this)"></i>
    </td>
</tr>