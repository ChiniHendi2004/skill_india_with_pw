@extends('layouts.app')

@section('pagetitle')
Create Course
@endsection


@php
$courseId = request()->route('id');
@endphp


@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold">Edit Course</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Course</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content px-4">
        <div id="responseMessage"></div>
        <div class="row">
            <div class="col-lg-4  ">
                <div class="">

                    <div class="card ">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="Course_form" enctype="multipart/form-data">
                            @csrf

                            <div class="card-body p-3">

                                <div class="mb-2">
                                    <label for="Course_group_id" class="form-label">Course Type</label>
                                    <select class="form-control" name="cg_id" id="Course_group_id">
                                        <option value="" selected disabled>Select Course</option>
                                    </select>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        <label for="sds" class="form-label">Course Name</label>
                                        <input type="text" class="form-control" id="sds" placeholder="Enter Course Type Name" name="name" value="{{ old('Course_name') }}">
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        <label for="customUrl" class="form-label">Image Thumbnail URL</label>
                                        <input class="form-control" type="url" id="customUrl" name="image" placeholder="Enter Thumbnail URL" value="{{ old('image') }}">
                                        <div id="imagePreview" class="mt-2"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="">
                                    <button type="submit" class="btn text-white submitCourse" style="background-color: #00008B">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <div class="col-lg-8 ">
                <div class="card align-middle">
                    <p class="d-flex justify-content-center font-weight-bold pt-2">Course Section</p>
                </div>
                <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                    <div class="card shadow" style="width: 600px;">
                        <div id="course_image" class="text-center p-3" style="height: 200px; overflow: hidden;">
                            <!-- Image will be injected here -->
                        </div>
                    </div>


                </div>
                <div class="card">
                    <div id="course_name" class="text-center">

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</div>



@section('scripts')
@section('scripts')
<script>
    $(document).ready(function() {
        list = '';
        Dropdownlist = $('#Course_group_id')
        $.ajax({
            type: "GET",
            url: `/Course/Groups/Active/List`,
            dataType: "json",
            success: function(response) {

                list = response
                for (let i = 0; i < response.length; i++) {
                    list = response[i];
                    Dropdownlist.append('<option value="' + list.id + '">' + list.name + '</option>');
                }
            }
        });
    });



    $(document).ready(function() {
        const courseId = "{{ $courseId }}";

        // Fetch course data and fill form + right-side preview
        function loadCourseData() {
            $.ajax({
                url: `/Select/Course/${courseId}`,
                method: 'GET',
                success: function(response) {
                    const data = response.data;

                    // Fill form
                    $('#Course_group_id').val(data.cg_id);
                    $('#sds').val(data.name);
                    $('#customUrl').val(data.image);
                    $('textarea[name="description"]').val(data.description);

                    // Right side preview
                    $('#course_image').html(`<img src="${data.image}" alt="Course Image" class="img-fluid rounded mb-2" style="max-height: 150px;">`);
                    $('#course_name').html(`<h5 class="text-center">${data.name}</h5>`);
                },
                error: function() {
                    alert('Failed to load course data.');
                }
            });
        }

        loadCourseData();

        // Form submission
        $('#Course_form').submit(function(e) {
            e.preventDefault();

            let formData = {
                cg_id: $('#Course_group_id').val(),
                name: $('#sds').val(),
                image: $('#customUrl').val(),
                description: $('textarea[name="description"]').val(),
                _method: 'PUT',
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: `/Update/Course/${courseId}`,
                method: 'POST',
                data: formData,
                success: function(res) {
                    $('#responseMessage').html(`<div class="alert alert-success">${res.message}</div>`);
                    loadCourseData(); // Refresh right side card
                },
                error: function(xhr) {
                    $('#responseMessage').html(`<div class="alert alert-danger">Update failed</div>`);
                }
            });
        });
    });
</script>
@endsection



@endsection

@endsection