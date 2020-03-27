<tr>
    <th scope="row">
        <input type="text" class="form-control order" name="order_snap[]" style="border-radius: 5px !important;border: 1px solid gray !important;background-color: white;" required value="{{ $order }}">
    </th>
    <td> 
        <select class="form-control" name="snapshot[]" onchange="view_snapshot(this.value,this)" required style="border-radius: 5px !important;border: 1px solid gray !important;background-color: white;">
            <option value="">Select</option>
            <option value="Website">Website</option>
            <option value="Facebook">Facebook</option>
            <option value="Linked In">Linked In</option> 
        </select>
    </td>
    <td> 
        <img src="" class="img-fluid" width="200px">
        <input type="hidden" name="snapshot_ss[]" value="" required>
    </td>
    <td> 
        <input type="text" class="form-control form-control-small mr-3" id="validationCustom04" required name="snapshot_duration[]" value="5">Seconds
    </td>
    <td> 
        <i class="fa fa-trash delete" aria-hidden="true" onclick="delete_row(this)"></i>
    </td>                               
</tr>