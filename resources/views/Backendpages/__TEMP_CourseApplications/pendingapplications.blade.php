@extends('layouts.app')

@section('pagetitle')
Pending Applications
@endsection

@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-9">
                    <h3 class="m-0" style="color: black;">Pending Applications</h3>
                </div><!-- /.col -->
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Pending Applications</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div id="responseMessage"></div>
    <div class="content px-2">
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex justify-content-end align-items-center">
                <h5 class="mt-3 text-primary fw-semibold">
                    <i class="fas fa-list-ol me-2"></i>
                    Total Applications: <span id="applicationCount">--</span>
                </h5>
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
                                <th>Name</th>
                                <th style="overflow:hidden; width:20px ;">Course</th>
                                <th>Applied on:</th>
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


@section('scripts')
@section('scripts')
<script>
    $(document).ready(function() {
        courseList();
        countApplications();
    });

    function courseList() {
        $('#data-table').DataTable().clear().destroy();
        $('#data-table tbody').empty();

        $.ajax({
            type: "GET",
            url: "/Pending/Applications/List",
            dataType: "json",
            success: function(response) {
                let counter = 1;
                $.each(response.data, function(index, item) {
                    let status = item.Application_status;
                    let applied_on = `${formatDate(item.Application_Created)}`;


                    let actions = `
                    <button class="btn btn-success btn-sm approve-btn mx-1" data-id="${item.id}" title="Approve">
                        <i class="fa-solid fa-square-check text-white"></i>
                    </button>
                    <button class="btn btn-danger btn-sm reject-btn mx-1" data-id="${item.id}" title="Reject">
                        <i class="fa-solid fa-xmark text-white"></i>
                    </button>
                `;
                    var viewCourse =
                        `<a href="/Viwe/Application/Page/${item.id}" class="btn py-2 mr-2 rounded  mx-1 " style="background-color: #16641b; font-size: 13px;"><i class="far fa-eye text-white"></i></a>`;


                    let row = `
                    <tr>
                        <td>${counter}</td>
                        <td>${item.name}</td>
                        <td>${item.Course_name}</td>
                        <td>${applied_on}</td>
                        <td>${status}</td>
                        <td>${actions} ${viewCourse}</td>
                    </tr>
                `;

                    $('#data-table tbody').append(row);
                    counter++;
                });

                $('#data-table').DataTable({
                    processing: true,
                    responsive: true,
                    ordering: true
                });
            }
        });
    }

    // Approve button click
    $(document).on('click', '.approve-btn', function() {
        const id = $(this).data('id');
        updateStatus(id, 'Approved');
    });

    // Reject button click
    $(document).on('click', '.reject-btn', function() {
        const id = $(this).data('id');
        updateStatus(id, 'Rejected');
    });

    // Status updater
    function updateStatus(id, status) {
        $.ajax({
            type: 'PATCH',
            url: `/Application/StatusChange/${id}`,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                status: status
            },
            success: function(response) {
                $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                courseList();
                countApplications();
            },
            error: function() {
                $('#responseMessage').html(`<div class="alert alert-danger">Failed to update status</div>`);
            }
        });


    }



    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }

    function countApplications() {
        $.ajax({
            url: '/Count/Applications', // Make sure this route exists
            method: 'GET',
            data: {
                status: 'Pending'
            },
            success: function(response) {
                $('#applicationCount').text(response.data);
            },
            error: function() {
                $('#applicationCount').text('Error');
            }
        });
    }
</script>
@endsection

@endsection

@endsection