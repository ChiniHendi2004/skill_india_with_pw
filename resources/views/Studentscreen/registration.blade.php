<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Registration</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body class="bg-light">

  <div class="container mt-5">
    <div class="card mx-auto" style="max-width: 600px;">
      <div class="card-body">
        <h3 class="text-center mb-4">Student Registration</h3>
        <div id="responseMessage" class="mb-3"></div>

        <form id="studentRegisterForm" enctype="multipart/form-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label for="name" class="form-label">Name *</label>
              <input type="text" class="form-control" id="name" name="name" required maxlength="255" />
            </div>

            <div class="col-md-6">
              <label for="father_name" class="form-label">Father's Name *</label>
              <input type="text" class="form-control" id="father_name" name="father_name" required maxlength="255" />
            </div>

            <div class="col-md-6">
              <label for="dob" class="form-label">Date of Birth *</label>
              <input type="date" class="form-control" id="dob" name="dob" required />
            </div>

            <div class="col-md-6">
              <label for="gender" class="form-label">Gender *</label>
              <select class="form-select" id="gender" name="gender" required>
                <option value="" selected disabled>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="mobile" class="form-label">Mobile *</label>
              <input type="text" class="form-control" id="mobile" name="mobile" required maxlength="15" />
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email *</label>
              <input type="email" class="form-control" id="email" name="email" required maxlength="255" />
            </div>

            <div class="col-12">
              <label for="address" class="form-label">Address *</label>
              <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
            </div>

            <div class="col-md-6">
              <label for="aadhar_no" class="form-label">Aadhar Number *</label>
              <input type="text" class="form-control" id="aadhar_no" name="aadhar_no" required maxlength="20" />
            </div>

            <div class="col-md-6">
              <label for="qualification" class="form-label">Qualification *</label>
              <input type="text" class="form-control" id="qualification" name="qualification" required maxlength="100" />
            </div>

            <div class="col-12">
              <label for="aadhar_pdf" class="form-label">Upload Aadhar PDF (optional)</label>
              <input type="file" class="form-control" id="aadhar_pdf" name="aadhar_pdf" accept="application/pdf" />
            </div>

            <div class="col-md-6">
              <label for="password" class="form-label">Password *</label>
              <input type="password" class="form-control" id="password" name="password" required minlength="6" />
            </div>

            <div class="col-md-6">
              <label for="password_confirmation" class="form-label">Confirm Password *</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="6" />
            </div>

            <div class="col-12">
              <button type="submit" class="btn btn-primary w-100">Register</button>
            </div>
          </div>
        </form>

        <div class="mt-3 text-center">
          Already have an account? <a href="/student/login/page">Login here</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(function () {
      $('#studentRegisterForm').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
          url: '/student/register',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          },
          success: function (response) {
            $('#responseMessage').html(
              '<div class="alert alert-success">' + response.message + '</div>'
            );
            $('#studentRegisterForm')[0].reset();
          },
          error: function (xhr) {
            let errors = xhr.responseJSON?.errors;
            let errorMessages = '';

            if (errors) {
              $.each(errors, function (key, messages) {
                errorMessages += messages.join('<br>') + '<br>';
              });
            } else if (xhr.responseJSON?.message) {
              errorMessages = xhr.responseJSON.message;
            } else {
              errorMessages = 'Registration failed. Please try again.';
            }

            $('#responseMessage').html(
              '<div class="alert alert-danger">' + errorMessages + '</div>'
            );
          },
        });
      });
    });
  </script>
</body>

</html>
