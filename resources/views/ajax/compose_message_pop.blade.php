<style>
    .jconfirm .jconfirm-box div.jconfirm-content-pane .jconfirm-content{
        overflow: hidden !important;
    }
    .harman{
        border-radius: 5px !important;
        border: 1px solid gray !important;
        background-color: white;
    }
</style>
<form class="" action="{{ route('send_email_to_contact') }}" method="POST" id="send_mail_form">
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-4 col-form-label">Email Subject</label>
        <div class="col-sm-8">
            <input type="text" class="form-control harman" id="subject" value="" name="subject">
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-4 col-form-label">Email Message</label>
        <div class="col-sm-8">
            <textarea class="form-control harman" id="description" rows="3" name="message"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="inputPassword" class="col-sm-4 col-form-label">Choose Template</label>
        <div class="col-sm-8">
            <select id="inputState" class="form-control harman" name="template" onchange="get_template_detail(this.value)">
                <option value="0">Select</option>
                @foreach($templates as $template)
                <option value="{{ $template->id }}">{{ $template->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{-- <div class="form-group row">
        <label for="inputPassword" class="col-sm-4 col-form-label">Send Via</label>
        <div class="col-sm-8">
            <div class="d-inline">
                <div class="form-check d-inline-block pl-1 ml-4 ml-md-0">
                    <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="1" aria-label="..."  name="linkedin">
                </div>
                <img src="{{ URL('public/assets/img/linkedin-colored.png') }}">
            </div>
            <div class="d-inline pl-4">
                <div class="form-check d-inline-block pl-1 ml-4 ml-md-0">
                    <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="1" aria-label="..." name="facebook">
                </div>
                <img src="{{ URL('public/assets/img/fb-colored.png') }}">
            </div>
            <div class="d-inline pl-4">
                <div class="form-check d-inline-block pl-1 ml-4 ml-md-0">
                    <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="1" aria-label="..." name="gmail">
                </div>
                <img src="{{ URL('public/assets/img/gmail-colored.png') }}">
            </div>
        </div>
    </div> --}}
    <input type="hidden" id="contact_id" value="{{ Crypt::encrypt($contact->id) }}">
    <input type="hidden" id="name" value="{{ $contact->first_name }} {{ $contact->last_name }}">
    <input type="hidden" id="email" value="{{ $contact->email }}">
    <input type="hidden" id="unsubscribed" value="{{ $contact->unsubscribed }}">
    <input type="hidden" name="contact" id="contacts_arr">
    <div class="row d-flex justify-content-center mt-5">
        {{-- <button type="submit" value="preview" class="btn preview-btn">Preview</button> --}}
        <button type="submit" value="send" class="btn preview-btn ml-5 mb-3">Send</button>
    </div>
</form>
<script>
    $("#send_mail_form").submit(function( event ) {
        contact = [];
        id      = $('#contact_id').val();
        name    = $('#name').val();
        email   = $('#email').val();
        unsubscribed=$('#unsubscribed').val();
        arr     = {"id":id,"name":name,"email":email,"unsubscribed":unsubscribed}; 
        contact.push(arr);

        $('#contacts_arr').val(JSON.stringify(contact));
    });
</script>