@extends('layouts.app')

@section('pagetitle')
View Student Details
@endsection

@section('content')
<div class="container mt-4">
    <div id="responseMessage"></div>

    <div class="card shadow border-0">
        <div class="card-header row text-black d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 col-7">Application Details</h5>

            <div class="col-5">
                <h6>Student Unique ID : <b><span id="unique_id">   </span></b></h6>
            </div>
        </div>
        <div class="card-body">
            <div id="appDetails" style="display: none;">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Name:</strong> <span id="studentName"></span></div>
                    <div class="col-md-6"><strong>Father's Name:</strong> <span id="fatherName"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Date of Birth:</strong> <span id="dob"></span></div>
                    <div class="col-md-6"><strong>Gender:</strong> <span id="gender"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Mobile:</strong> <span id="mobile"></span></div>
                    <div class="col-md-6"><strong>Email:</strong> <span id="email"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12"><strong>Address:</strong> <span id="address"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Qualification:</strong> <span id="qualification"></span></div>
                    <div class="col-md-6"><strong>Aadhar No:</strong> <span id="aadharNo"></span></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Aadhar Document:</strong>
                        <a href="#" id="aadharPdf" target="_blank" class="btn btn-sm btn-outline-secondary">View PDF</a>
                    </div>
                </div>

                <hr>
                <h5 class="mb-3 text-secondary">Applied Course</h5>
                <div class="mb-2">
                    <strong>Course Name:</strong> <span id="courseName"></span>
                </div>
                <div class="mb-3">
                    <strong>Status:</strong> <span id="applicationStatus" class="fw-bold text-uppercase text-primary"></span>
                </div>


                <div class="mt-4 text-end">
                    <button class="btn btn-success me-2" id="approve-btn">Accept</button>
                    <button class="btn btn-danger" id="reject-btn">Reject</button>
                </div>
            </div>

            <div id="loadingMsg" class="text-center text-muted">
                Loading application details...
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let appId = "{{ request()->route('id') }}";

        // Load application details
        fetchApplication();

        function fetchApplication() {
            $.ajax({
                url: `/Viwe/Application/${appId}`,
                type: 'GET',
                success: function(res) {
                    console.log(res)
                    if (res.data) {
                        let d = res.data;
                        $('#unique_id').text(d.unique_sid || '-');
                        $('#studentName').text(d.name || '-');
                        $('#fatherName').text(d.father_name || '-');
                        $('#dob').text(d.dob || '-');
                        $('#gender').text(d.gender || '-');
                        $('#mobile').text(d.mobile || '-');
                        $('#email').text(d.email || '-');
                        $('#address').text(d.address || '-');
                        $('#qualification').text(d.qualification || '-');
                        $('#aadharNo').text(d.aadhar_no || '-');
                        $('#aadharPdf').attr('href', `/storage/${d.aadhar_pdf}`);
                        $('#courseName').text(d.Course_name || '-');
                        $('#applicationStatus').text(d.Application_status || 'Pending');

                        // Button display logic
                        $('#approve-btn').hide();
                        $('#reject-btn').hide();
                        $('#make-pending-btn').remove();

                        if (d.Application_status === 'Pending') {
                            $('#approve-btn').show().text('Approve');
                            $('#reject-btn').show().text('Reject');
                        } else if (d.Application_status === 'Approved') {
                            $('#reject-btn').after(`<button class="btn btn-warning me-2" id="make-pending-btn">Make Pending</button>`);
                        } else if (d.Application_status === 'Rejected') {
                            $('#approve-btn').show().text('Approve');
                            $('#approve-btn').after(`<button class="btn btn-warning me-2" id="make-pending-btn">Make Pending</button>`);
                        }

                        $('#loadingMsg').hide();
                        $('#appDetails').show();
                    } else {
                        $('#loadingMsg').html('<div class="text-danger">Application not found.</div>');
                    }
                },
                error: function() {
                    $('#loadingMsg').html('<div class="text-danger">Failed to load application.</div>');
                }
            });
        }

        // Button handlers
        $(document).on('click', '#approve-btn', function() {
            updateStatus(appId, 'Approved');
        });

        $(document).on('click', '#reject-btn', function() {
            updateStatus(appId, 'Rejected');
        });

        $(document).on('click', '#make-pending-btn', function() {
            updateStatus(appId, 'Pending');
        });

        // Update status
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
                    fetchApplication(); // Refresh data and buttons
                },
                error: function() {
                    $('#responseMessage').html(`<div class="alert alert-danger">Failed to update status</div>`);
                }
            });
        }
    });
</script>

@endsection