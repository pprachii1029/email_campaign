@extends('layouts/app')

@section('content')
<!-- Page Content  -->
<section id="content">

    <!--Title Bar-->

    <div class="container-fluid title-bar">
        <div class="row">
            <div class="col-md-4 mt-2">
                <h3 class="page-title pl-3">Add Group</h3>
            </div>
            <div class="col-md-8 d-flex justify-content-end">

            </div>
        </div>
    </div>

    <!--Main Content-->
    <div class="container-fluid mt-4 ">
        <div class="row main-side pt-4 pb-5">
            <div class="col-md-12">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                        <form action="{{ route('add_group') }}" method="POST" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">&emsp;</label>
                                <div class="col-sm-6">
                                    <img src="{{ (@$contact) ? URL(@$contact->picture) : '' }}" id="pp_preview" width="150px">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Add Image</label>
                                <div class="col-sm-6">
                                    <div class="upload-btn-wrapper1">
                                        <button class="btn-upload1">Upload
                                            <img src="{{ URL('public/assets/img/upload-img.png') }}">
                                        </button>
                                        <input type="file" name="picture"  onchange="filePreview(this,'pp_preview')" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Group Name</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="inputPassword" placeholder="Group Name" name="group_name" required>
                                </div>
                            </div>
                           
                            <div class="text-center">
                                <button type="submit" class="btn save-btn ">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
<script>
    function filePreview(input,id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#'+id).attr('src',e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function(){
        $('#m1').addClass('active1');
    });
</script>
@endsection