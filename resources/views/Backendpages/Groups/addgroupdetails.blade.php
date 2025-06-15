@extends('layouts.app')

@section('pagetitle')
Group Details || Create
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0" style="color: black;">Create Group Details</h2>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active"> Group Details</li>
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
                            <input type="hidden" id="g_id" name=g_id value="{{ $g_id }}">
                            <div class="mb-3">
                                <label for="value" class="form-label"> value</label>
                                <input type="text" class="form-control" id="value" placeholder="Enter Group Value" name="value">
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn text-white btn-primary" style="background-color: #00008B; width: 25rem;">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body table-responsive p-3" style="height: 538px">
                        <table class="table table-bordered table-hover text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Values</th>
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
                        <label for="group_name" class="form-label">Value</label>
                        <input type="text" class="form-control" id="group_name" name="value" placeholder="Enter Value name" required>
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
        // Call your function here
        fetchDataAndPopulateTable();
    });


    var g_id = $('#g_id').val();

    function fetchDataAndPopulateTable() {
        $.ajax({
            url: `/Group/Details/List/${g_id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {

                let counter = 1;
                $('#data-table tbody').empty();

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
                    let row = `<tr><td>${counter}</td><td>${item.value}</td><td>${statusSelect}</td><td>${editButton} ${deleteButton}</td></tr>`;
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
                url: '/Create/Groups/Details',
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
    });




    // Edit button click
    $(document).on('click', '.editbtn', function() {
        const id = $(this).data('content-id');
        $('#id').val(id);
        $('#editModal').modal('show');

        $.ajax({
            type: 'GET',
            url: `/Group/Details/Select/${id}`,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                let data = response;
                $('#group_name').val(data.value);
                $('.modal-title-update').text(` ${data.value}`);
                fetchDataAndPopulateTable();
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
            url: `/Group/Details/Edit/${id}`,
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
            url: `/Group/Details/delete/${id}`,
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
            url: `/Group/Details/StatusChange/${id}`,
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