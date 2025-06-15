<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">
    <title>Course Details </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f2f4f7;
            font-family: 'Poppins', sans-serif;
        }

        .hero-header {
            background: linear-gradient(to right, #fd7e14, #fbb034);
            padding: 40px;
            border-radius: 20px;
            color: #fff;
            display: flex;
            gap: 30px;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .hero-header img {
            width: 100%;
            max-width: 350px;
            border-radius: 15px;
            object-fit: cover;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .hero-info h1 {
            font-weight: 800;
            font-size: 2.4rem;
        }

        .hero-info .meta {
            margin-top: 20px;
        }

        .meta span {
            margin-right: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.95rem;
        }

        .enroll-box {
            display: flex;
            margin-top: 25px;
            gap: 10px;
        }

        .enroll-box input {
            border-radius: 10px;
            border: 2px solid #ffc107;
        }

        .tabs {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            border-bottom: 2px solid #e1e5ea;
        }

        .tabs button {
            background: none;
            border: none;
            font-weight: 600;
            padding: 14px 24px;
            cursor: pointer;
            font-size: 1rem;
            color: #333;
            border-radius: 10px 10px 0 0;
            transition: 0.3s;
        }

        .tabs button.active {
            background: #ffffff;
            color: #fd7e14;
            border: 2px solid #ddd;
            border-bottom: none;
        }

        .details-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            transition: 0.3s ease;
        }

        .details-card h5 {
            font-weight: 700;
            margin-bottom: 15px;
        }

        .badge-custom {
            padding: 8px 14px;
            display: inline-block;

            font-size: 0.9rem;
            margin: 4px 6px 4px 0;
            border-radius: 20px;
            background: #e9ecef;
            color: #333;
            font-weight: 500;
        }

        .details-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div id="responceMessage" class="mt-3"></div>

        <div class="hero-header">

            <div class="col-md-4">
                <img id="course-image" src="" alt="Course Image">
            </div>
            <div class="hero-info col-md-8">
                <h1 id="course-name">Loading...</h1>
                <p id="course-description" class="mt-2"></p>
                <div class="meta mt-3">
                    <!-- <span><i class="fas fa-building"></i> <strong id="training-partner">--</strong></span>
                    <span><i class="fas fa-tag"></i> <strong id="course-category">--</strong></span>
                    <span><i class="fas fa-user-graduate"></i> <strong id="enrollment-count">--</strong></span> -->
                    <span><i class="fas fa-clock"></i> <strong id="course-duration"></strong></span>
                </div>

                <div class="enroll-box">
                    <button class="btn btn-light text-dark fw-bold  enroll-btn"><i class="fas fa-paper-plane"></i> ENROLL</button>
                </div>
            </div>
        </div>

        <div class="tabs">
            <button class="active" onclick="showTab('details')">Course Details</button>

        </div>

        <div id="details-tab">
            <div id="groups-container"></div>
        </div>


        <input type="hidden" id="courseId" value="{{ request()->query('id') }}">
        <input type="hidden" id="studentId" value="{{ session('student_id') }}">




    </div>

    <script>
        $(document).ready(function() {
            const courseId = window.location.pathname.split('/').pop();
            $('#courseId').val(courseId); // Fix hidden input value

            $.ajax({
                url: `/Get/Course/Details/${courseId}`,
                method: 'GET',
                success: function(response) {
                    console.log(response);

                    // Populate basic course info
                    $('#course-name').text(response.course_name);
                    $('#course-image').attr('src', response.course_image);
                    $('#course-description').text(response.course_details.description);
                    $('#course-duration').text(response.formatted_duration);
                    $('#training-partner').text(response.groups.group_name);

                    // Populate groups and values
                    const groupsContainer = $('#groups-container');
                    groupsContainer.empty();

                    let grouped = {};
                    response.groups.forEach(item => {
                        if (!grouped[item.group_name]) {
                            grouped[item.group_name] = [];
                        }
                        grouped[item.group_name].push(item.group_value);
                    });

                    // Create a single row to hold all the group cards
                    let groupRow = $('<div class="row"></div>');

                    $.each(grouped, function(groupName, values) {
                        let groupHTML = `
                <div class="col-3 mb-3">
                    <div class="details-card p-3 h-100 w-100">
                        <h5>${groupName}</h5>
                        <div>`;

                        values.forEach(value => {
                            groupHTML += `<span class="badge-custom me-1 mb-1">${value}</span>`;
                        });

                        groupHTML += `
                        </div>
                    </div>
                </div>`;

                        groupRow.append(groupHTML);
                    });

                    // Append the complete row to the container
                    groupsContainer.append(groupRow);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to load course details.');
                }
            });

        });

        function showTab(tab) {
            $('#details-tab, #additional-tab, #topics-tab, #eligibility-tab').hide();
            $('#' + tab + '-tab').show();
            $('.tabs button').removeClass('active');
            $('.tabs button').each(function() {
                if ($(this).attr('onclick').includes(tab)) {
                    $(this).addClass('active');
                }
            });
        }


        $('.enroll-btn').on('click', function() {
            const courseId = $('#courseId').val(); // ✅ Get from hidden input
            const studentId = $('#studentId').val();



            $.ajax({
                url: '/enroll',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    course_id: courseId,
                    student_id: studentId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#responceMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                },
                error: function(xhr) {
                    console.log(xhr.responseJSON);
                    $('#responceMessage').html(`<div class="alert alert-danger">${xhr.responseJSON?.message ?? 'An error occurred.'}</div>`);
                }
            });
        });
    </script>


</body>

</html>