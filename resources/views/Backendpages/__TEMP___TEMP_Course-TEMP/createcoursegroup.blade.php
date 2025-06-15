@extends('layouts.app')

@section('pagetitle')
Course Group || Create
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0" style="color: black;">Create Course Group</h2>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Create Course Group</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">

        <div id="responseMessage"></div>

        <div class="row px-2 mt-3">
            <div class="col-lg-6">
                <div class="card p-3">
                    <form method="POST" id="myForm">
                        @csrf
                        <div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Course Group Name</label>
                                <input type="text" class="form-control" id="course_name" placeholder="Enter Course Group" name="name">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Image URL</label>
                            <input type="text" class="form-control" name="image">
                        </div>

                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 25rem;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body table-responsive p-3" style="height: 548px">
                        <table class="table table-bordered table-hover text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Groups</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
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

<!-- Delet Model -->

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
    function clearTable() {
        $('#data-table tbody').empty();
    }


    $(document).ready(function() {
        // Call your function here
        fetchDataAndPopulateTable();
    });



    function fetchDataAndPopulateTable() {
        clearTable();
        $.ajax({
            url: '/Course/Groups/Active/List',
            type: 'GET',
            dataType: 'json',
            success: function(response) {

                let counter = 1;
                $.each(response, function(index, item) {
                    let statusSelect =
                        `<select class="form-select select-status" data-id="${item.id}">
                            <option value="1" ${item.status == 1 ? 'selected' : ''}>Active</option>
                            <option value="0" ${item.status == 0 ? 'selected' : ''}>Inactive</option>
                        </select>`;
                    let editButton =
                        `<button class="btn btn-primary btn-sm editbtn ms-4" data-content-id="${item.id}"><i class="fas fa-edit"></i></button>`;
                    let deleteButton =
                        `<button class="btn btn-danger btn-sm deletebtn" data-content-id="${item.id}"><i class="fas fa-trash-alt"></i></button>`;
                    let row = `<tr><td>${counter}</td><td>${item.name}</td><td>${statusSelect}</td><td>${editButton} ${deleteButton}</td></tr>`;
                    $('#data-table tbody').append(row);
                    counter++;
                });
            },
            error: function() {
                alert('Error fetching data.');
            }
        });
    }

    $(document).ready(function() {


        $('#myForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/Create/Course/Group',
                data: $(this).serialize(),
                success: function(response) {
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                    $('#myForm')[0].reset();
                    fetchDataAndPopulateTable();
                },
                error: function() {
                    $('#responseMessage').html('<div class="alert alert-danger">Error submitting the form</div>');
                }
            });
        });


        $(document).on('click', '.editbtn', function() {
            let id = $(this).data('content-id');
            $('#id').val(id);
            $('#editModal').modal('show');
            $.ajax({
                type: 'GET',
                url: `/Course/Group/Select/${id}`,
                success: function(response) {
                    $('#group_name').val(response.name);
                    $('#group_image').val(response.image);
                    $('#group_description').val(response.description);
                    $('.modal-title-update').text(` ${response.name}`);
                }
            });
        });

        $(document).on('click', '.deletebtn', function() {
            var id = $(this).data('content-id');
            $('#id').val(id);
            $('#myModaldel').modal('show');

            $.ajax({
                type: "GET",
                url: `/Course/Group/Select/${id}`,
                success: function(response) {
                    $('#group_name').val(response.data.group_name);
                    $('.modal-title').html(`${response.data.group_name}`)

                },
            });
        });







        // Edit button click
        $(document).on('click', '.editbtn', function() {
            const id = $(this).data('content-id');
            $('#id').val(id);
            $('#editModal').modal('show');

            $.ajax({
                type: 'GET',
                url: `/Course/Group/Select/${id}`,
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
                url: `/Course/Group/Edit/${id}`,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: $(this).serialize(),
                success: function(response) {
                    $('#editModal').modal('hide');
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                    fetchDataAndPopulateTable();
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
                url: `/Course/Group/delete/${id}`,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#myModaldel').modal('hide');
                    $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                    fetchDataAndPopulateTable();
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
                url: `/Course/Group/StatusChange/${id}`,
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
    });
</script>
@endsection
@endsection