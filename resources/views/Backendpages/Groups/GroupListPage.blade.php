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
                                <th>Groups</th>
                                <th style="overflow:hidden; width:20px ;">Description</th>
                                <th>Add Values</th>
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


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="GroupForm">
                <div class="modal-header">
                    <h5 class="modal-title modal-title-update" id="editModalLabel">Edit Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="id" name="id">

                    <div class="mb-3">
                        <label for="group_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="group_name" name="name" placeholder="Enter group name" required>
                    </div>

                    <div class="mb-3">
                        <label for="group_image" class="form-label">Image URL</label>
                        <input type="text" class="form-control" id="group_image" name="image" placeholder="Enter image URL">
                    </div>

                    <div class="mb-3">
                        <label for="group_description" class="form-label">Description</label>
                        <textarea class="form-control" id="group_description" name="description" rows="3" placeholder="Enter description"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="myModaldel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Are you sure you want to delete?</h5>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c">Delete</button>
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
        $.ajax({
            type: "GET",
            url: "/Group/List",
            dataType: "json",
            success: function(response) {

                var counter = 1;
                $.each(response, function(index, item) {
                    var viewCourse =
                        `<a href="/Viwe/Group/Details/Page/${item.id}" class="btn py-2 mr-2 rounded  mx-1 " style="background-color: #16641b; font-size: 13px;"><i class="far fa-eye text-white"></i></a>`;
                    var editButton =
                        `<button class="btn btn-primary btn-sm editbtn ms-4" data-content-id="${item.id}"><i class="fas fa-edit"></i></button>`;
                    var deleteButton =
                        `<button class="btn py-2 rounded deletebtn text-white" data-content-id="${item.id}" style="background-color: #d70c1a; font-size: 13px;"><i class="fas fa-trash-alt text-white"></i></button>`;

                    var addDetails =
                        `<a href="/Add/Groups/Details/Page/${item.id}" class="btn py-2 mr-2 rounded mx-1 text-white" style="background-color:rgb(59, 8, 146); font-size: 13px;"><i class="far fa-add text-white"></i></a>`;
                    var row = `<tr>
                    <td>${counter}</td>
                    <td class="responsive-text">${item.name}</td>
                    <td style="overflow:hidden; width:20px ;">${item.description}</td>
                    <td class="responsive-text">${addDetails}</td>
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




    // Edit button click
    $(document).on('click', '.editbtn', function() {
        const id = $(this).data('content-id');
        $('#id').val(id);
        $('#editModal').modal('show');

        $.ajax({
            type: 'GET',
            url: `/Group/Select/${id}`,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                const data = response.data;
                $('#group_name').val(data.name);
                $('#group_image').val(data.image);
                $('#group_description').val(data.description);
                $('.modal-title-update').text(`Edit Group - ${data.name}`);
            },
            error: function() {
                alert('Failed to fetch group details.');
            }
        });
    });

    // Submit Edit
    $('#GroupForm').submit(function(e) {
        e.preventDefault();
        const id = $('#id').val(); // ← FIXED: get the hidden input value

        $.ajax({
            type: 'POST',
            url: `/Group/Edit/${id}`,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                courseList();
            },
            error: function() {
                alert('Failed to update group.');
            }
        });
    });

    // Delete button click
    $(document).on('click', '.deletebtn', function() {
        const id = $(this).data('content-id');
        $('#id').val(id); // Hidden input for deletion
        $('#myModaldel').modal('show');
    });

    // Confirm Delete
    $('#myModaldel').on('click', '#delete-confirm-button', function() {
        const id = $('#id').val();
        $.ajax({
            type: 'DELETE',
            url: `/Group/delete/${id}`,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#myModaldel').modal('hide');
                $('#responseMessage').html(`<div class="alert alert-danger">${response.message}</div>`);
                courseList();
            },
            error: function() {
                alert('Failed to delete group.');
            }
        });
    });

    // Change status
    $('#data-table').on('change', '.select-status', function() {
        const id = $(this).data('id');
        const value = $(this).val();
        $.ajax({
            type: 'PATCH',
            url: `/Group/StatusChange/${id}`,
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