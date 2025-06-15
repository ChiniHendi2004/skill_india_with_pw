<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Enrolled Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .course-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: 0.3s;
        }

        .course-card:hover {
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
        }

        .course-image {
            height: 180px;
            object-fit: cover;
        }

        .status-badge {
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4">My Enrolled Courses</h2>
        <div class="row" id="courseContainer">
            <!-- Courses will be loaded here -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/student/get-enrolled-courses',
                method: 'GET',
                success: function(response) {
                    console.log(response);
                    
                    if (response.success) {
                        let html = '';
                        response.courses.forEach(course => {
                            html += `
                            <div class="col-md-4 mb-4">
                                <div class="card course-card">
                                    <img src="${course.image}" class="card-img-top course-image" alt="${course.title}">
                                    <div class="card-body">
                                        <h5 class="card-title">${course.name}</h5>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item  text-dark">Fees: ₹${course.fee}</li>
                                            <li class="list-group-item">Duration: ${course.formatted_duration}</li>
                                            <li class="list-group-item">Status: 
                                                <span class="badge  text-dark status-badge">${course.status}</span>
                                            </li>
                                              <li class="list-group-item">Applied on: 
                                                <span class="badge  text-dark status-badge">${formatDate(course.created_at)}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>`;
                        });
                        $('#courseContainer').html(html);
                    } else {
                        $('#courseContainer').html('<div class="alert alert-warning">No enrolled courses found.</div>');
                    }
                },
                error: function() {
                    $('#courseContainer').html('<div class="alert alert-danger">Error loading courses.</div>');
                }
            });
        });

        function formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString(undefined, options);
        }
    </script>
</body>

</html>