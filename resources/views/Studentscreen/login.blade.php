<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h3 class="text-center mb-4">Student Login</h3>
                <div id="responseMessage" class="mt-3"></div>
                <form id="studentLoginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="unique_sid" class="form-label">Unique ID</label>
                        <input type="text" class="form-control" id="unique_sid" name="unique_sid" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <div class="row mt-3">
                    <div class="text-center col-6">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgotModal">Forgot Unique ID?</a>
                    </div>
                    <div class="text-center col-6">
                        <a href="/student/Registraion/Page">Register</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Aadhar Recovery -->
    <div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="recoverForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="forgotModalLabel">Recover Unique ID</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="aadhar_no" class="form-label">Enter Aadhar Number</label>
                            <input type="text" class="form-control" id="aadhar_no" name="aadhar_no" required>
                        </div>
                        <div id="recoverResponse"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Get Unique ID</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Student Login
            $('#studentLoginForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "/student/login",
                    data: {
                        password: $('#password').val(),

                        unique_sid: $('#unique_sid').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                            localStorage.setItem('student_id', response.student_id);
                            window.location.href = '/student/courses/section';
                        } else {
                            $('#responseMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#responseMessage').html('<div class="alert alert-danger">Login failed. Please try again.</div>');
                    }
                });
            });

            // Aadhar Recovery
            $('#recoverForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "/FindUniqueId",
                    data: {
                        aadhar_no: $('#aadhar_no').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#recoverResponse').html(`<div class="alert alert-success">Your Unique Student ID is: <strong>${response.unique_sid}</strong></div>`);
                    },
                    error: function() {
                        $('#recoverResponse').html(`<div class="alert alert-danger">Student not found with this Aadhar number.</div>`);
                    }
                });
            });
        });
    </script>

</body>

</html>