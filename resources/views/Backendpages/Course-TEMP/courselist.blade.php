@extends('layouts.app')

@section('pagetitle')
Course || List
@endsection

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h3 class="m-0" style="color: black;">Course List</h3>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Course List</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div id="responseMessage"></div>
    <div class="content px-2">
        <div class=" card ">
            <div class="my-2 mx-4 d-flex justify-content-end">
                <a href="{{ url('/Create/Course/Page') }}">
                    <button type="submit" class="btn text-white" style="background-color: #00008B"><i
                            class="fas fa-plus-circle pe-2"></i>Add new</button>
                </a>
            </div>
        </div>
        <div>
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body table-responsive px-4  pt-4">
                    <table class="table table-bordered table-hover table-head-fixed text-nowrap" id="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course</th>
                                <th style="overflow:hidden; width:20px ;">Description</th>
                                <th>Add Details</th>
                                <th>Status</th>
                                <th class="px-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModaldel" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal header -->
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Title</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <h5 class="text-center">This Will Remove All The Content's of</h5>
                <h5 class="text-center" id="details-title"></h5>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c;">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




@section('scripts')
<script>
    $(document).ready(function() {
        courseList();
    });

    // Fetch and update course list
    function courseList() {


        $('#data-table tbody').empty();
        $.ajax({
            type: "GET",
            url: "/Course/List",
            dataType: "json",
            success: function(response) {
                var counter = 1;
                $.each(response.data, function(index, item) {

                    let statusSelect =
                        `<select class="form-select select-status" data-id="${item.id}">
                                <option value="1" ${item.status == 1 ? 'selected' : ''}>Active</option>
                                <option value="0" ${item.status == 0 ? 'selected' : ''}>Inactive</option>
                            </select>`;
                    var viewCourse =
                        `<a href="/Viwe/Course/Details/Page/${item.id}" class="btn py-2 mr-2 rounded  mx-1 " style="background-color: #16641b; font-size: 13px;"><i class="far fa-eye text-white"></i></a>`;
                    var editButton =
                        `<a href="/Edit/Course/Page/${item.id}" class="btn py-2 mr-2 rounded editbtn text-white" style="background-color: #00008B; font-size: 13px;"><i class="fas fa-edit text-white"></i></a>`;
                    var deleteButton =
                        `<button class="btn py-2 rounded deletebtn text-white" data-updates-id="${item.id}" style="background-color: #d70c1a; font-size: 13px;"><i class="fas fa-trash-alt text-white"></i></button>`;

                    var addDetails =
                        `<a href="/Add/Course/Details/Page/${item.id}" class="btn py-2 mr-2 rounded mx-1 text-white" style="background-color:rgb(59, 8, 146); font-size: 13px;"><i class="far fa-add text-white"></i></a>`;
                    var row = `<tr>
                    <td>${counter}</td>
                    <td class="responsive-text">${item.name}</td>
                    <td style="overflow:hidden; width:20px ;">${item.description}</td>
                    <td class="responsive-text">${addDetails}</td>
                    <td class="responsive-text">${statusSelect}</td>
                    <td>${editButton} ${viewCourse} ${deleteButton}</td>
                </tr>`;

                    $('#data-table tbody').append(row);
                    counter++;
                });

                $('#data-table').DataTable({
                    deferRender: true,
                    processing: true,
                    ordering: true,
                    searching: true,
                });
            }
        });
    }

    // Show delete modal
    var course_id;
    $(document).on('click', '.deletebtn', function() {
        course_id = $(this).data('updates-id');
        $('#myModaldel').modal('show');

        $.ajax({
            type: "GET",
            url: `/course/delete/${course_id}`,
            dataType: "json",
            success: function(response) {
                $('.modal-title').html(response.data.title);
                $('#details-title').html(response.data.title);
            }
        });
    });

    // Delete course on confirmation
    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        $.ajax({
            type: "DELETE",
            url: "/course/delete/" + course_id, // Fixed URL
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#myModaldel').modal('hide');
                $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                courseList();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });



    // Change status
    $('#data-table').on('change', '.select-status', function() {
        const id = $(this).data('id');
        const value = $(this).val();
        $.ajax({
            type: 'PATCH',
            url: `/Course/StatusChange/${id}`,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                status: value
            },
            success: function(response) {
                $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
            },
            error: function() {
                alert('Failed to update status.');
            }
        });
    });
</script>

@endsection

@endsection