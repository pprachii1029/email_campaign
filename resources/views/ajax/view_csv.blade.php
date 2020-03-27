<form method="POST" action="{{ route('save_csv_contacts') }}">
<table class="table tab1 table-responsive">
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
        @foreach($data as $row)
        <tr>
            <th style="width: 5%;align-items: center;">
                <img class="contact-img" src="{{ URL('public/assets/img/john-doe.png') }}">
            </th>
            <td style="width:10%">{{ @$row['first_name'] }} {{ @$row['last_name'] }}</td>
            <td style="width:15%">{{ @$row['phone_number'] }}</td>
            <td style="width:15%">{{ @$row['website'] }}</td>
            <td style="width:15%">{{ @$row['email'] }}</td>
            <td style="width:15%; border:1px; color:black; solid !important">
                <input type="hidden" name="first_name[]" value="{{ @$row['first_name'] }}">
                <input type="hidden" name="last_name[]" value="{{ @$row['last_name'] }}">
                <input type="hidden" name="phone_number[]" value="{{ @$row['phone'] }}">
                <input type="hidden" name="website[]" value="{{ @$row['website'] }}">
                <input type="hidden" name="email[]" value="{{ @$row['email'] }}">
                <input type="hidden" name="designation[]" value="{{ @$row['designation'] }}">
                <input type="hidden" name="facebook[]" value="{{ @$row['facebook'] }}">
                <input type="hidden" name="linkedin[]" value="{{ @$row['linkedin'] }}">
                <input type="hidden" name="notes[]" value="{{ @$row['notes'] }}">

                <div class="action1"> <i class="fa fa-trash delete" aria-hidden="true" onclick="delete_csv(this)"></i>
                    <div class="action1-inner">
                        <img src="public/assets/img/record-audio.png">
                        <p>Audio</p>
                    </div>
                    </i>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
    </thead>
</table>
<div class="col-md-6">
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-2 col-form-label">Choose List</label>
        <div class="col-sm-6">
            <select id="inputState" class="form-control ppc" name="group" required>
                @foreach($groups as $group)
                <option value="{{  $group->id }}">{{ $group->group_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="col-md-12 text-center">
    <button type="submit" class="btn btn-success">Save</button>
    &emsp;
    <button type="button" class="btn btn-danger">Cancel</button>
</div>
</form>