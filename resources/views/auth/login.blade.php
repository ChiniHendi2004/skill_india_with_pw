<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

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
                <h3 class="text-center mb-4">Admin Login</h3>
                <div id="responseMessage" class="mt-3"></div>
                <form id="adminLoginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Student Login
            $('#adminLoginForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    type: "POST",
                    url: "/Admin/Login",
                    data: formData,
                    processData: false, // Don't process the data
                    contentType: false, // Let the browser set the correct content type
                    success: function(response) {
                        if (response.success) {
                            $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                            localStorage.setItem('admin_id', response.admin_id);
                            window.location.href = '/Admin/Dashboard';
                        } else {
                            $('#responseMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#responseMessage').html('<div class="alert alert-danger">Login failed. Please try again.</div>');
                    }
                });
            });


        });
    </script>

</body>

</html>