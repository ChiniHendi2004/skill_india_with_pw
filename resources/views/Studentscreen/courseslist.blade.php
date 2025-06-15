<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Available Courses | Student Section</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: #f9f9f9;
    }

    h2 {
      font-weight: 600;
    }

    .accordion-button:focus {
      box-shadow: none;
    }

    .card img {
      height: 200px;
      object-fit: cover;
    }

    .filter-search {
      font-size: 0.9rem;
    }

    .search-bar {
      position: relative;
    }

    .search-bar i {
      position: absolute;
      top: 10px;
      left: 12px;
      color: #888;
    }

    .search-bar input {
      padding-left: 36px;
    }

    .reset-btn i {
      margin-right: 5px;
    }

    .no-courses {
      font-size: 1.1rem;
      padding: 40px 0;
    }

    .card-title {
      font-weight: 600;
      font-size: 1.1rem;
    }
  </style>
</head>

<body>

  <div class="container py-4">
    <div class="row align-items-center mb-3">
      <div class="col-md-10">
        <h2 class="text-center text-md-start">Available Courses</h2>
      </div>
      <div class="col-md-2 text-md-end text-center mt-2 mt-md-0">
        <a href="/student/enrolled/courses" class="btn btn-outline-secondary">
          <i class="bi bi-bookmark-check"></i> Enrolled
        </a>
        <a href="/student/logout">Logout</a>
      </div>
    </div>

    <div class="row">
      <!-- Filters -->
      <div class="col-md-3 mb-4">
        <h5><i class="bi bi-filter-circle me-2"></i>Filters</h5>
        <!-- Search Bar -->

        <div class="search-bar mb-4">
          <i class="bi bi-search"></i>
          <input type="text" id="searchInput" class="form-control" placeholder="Search courses...">
        </div>

        <div class="accordion" id="filterAccordion">
          <form id="filterForm"></form>
        </div>
        <button id="resetFilters" class="btn btn-outline-primary mt-3 w-100 reset-btn">
          <i class="bi bi-arrow-clockwise"></i> Reset Filters
        </button>
      </div>

      <!-- Courses and Search -->
      <div class="col-md-9" style="overflow: scroll; height: 1200px;">


        <!-- Course Cards -->
        <div class="row" id="coursesList"></div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      loadFilters();
      loadCourses();

      // Load filter groups via API
      function loadFilters() {
        $.ajax({
          url: '/groups',
          method: 'GET',
          success: function(groups) {
            let html = '';
            groups.forEach((group, index) => {
              html += `
                <div class="accordion-item mb-2">
                  <h2 class="accordion-header" id="heading${index}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                      data-bs-target="#collapse${index}" aria-expanded="false" aria-controls="collapse${index}">
                      ${group.name}
                    </button>
                  </h2>
                  <div id="collapse${index}" class="accordion-collapse collapse"
                    aria-labelledby="heading${index}" data-bs-parent="#filterAccordion">
                    <div class="accordion-body">
                      <input type="text" class="form-control mb-2 filter-search" placeholder="Search ${group.name}...">
                      <div class="filter-options">`;

              group.group_details.forEach(detail => {
                html += `
                        <div class="form-check">
                          <input class="form-check-input filter-checkbox" type="checkbox" name="filters[${group.id}][]" value="${detail.id}" id="filter_${detail.id}">
                          <label class="form-check-label" for="filter_${detail.id}">${detail.value}</label>
                        </div>`;
              });

              html += `
                      </div>
                    </div>
                  </div>
                </div>`;
            });

            $('#filterForm').html(html);
          }
        });
      }

      // Load all or filtered courses via AJAX
      function loadCourses() {
        let formData = $('#filterForm').serialize();
        let searchQuery = $('#searchInput').val();
        formData += '&search=' + encodeURIComponent(searchQuery);

        $.ajax({
          url: '/courses/filter',
          method: 'GET',
          data: formData,
          success: function(courses) {
            renderCourses(courses);
          },
          error: function() {
            alert('Failed to load courses.');
          }
        });
      }

      // Render courses dynamically
      function renderCourses(courses) {
        let html = '';
        if (courses.length > 0) {
          courses.forEach(course => {
            html += `
              <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                  <img src="${course.image}" class="card-img-top" alt="${course.name}">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${course.name}</h5>
                    <p class="card-text text-muted small">${course.description ?? 'No description available'}</p>
                    <a href="/student/courses/apply/${course.id}" class="btn btn-primary mt-auto w-100">
                      <i class="bi bi-send-check me-1"></i> Apply
                    </a>
                  </div>
                </div>
              </div>`;
          });
        } else {
          html = '<div class="col-12 text-center text-muted no-courses"><i class="bi bi-emoji-frown"></i> No courses found.</div>';
        }

        $('#coursesList').html(html);
      }

      // Trigger course loading when filter checkboxes change
      $(document).on('change', '.filter-checkbox', function() {
        loadCourses();
      });

      // Reset filters button
      $('#resetFilters').click(function() {
        $('#filterForm')[0].reset();
        $('.filter-search').val('');
        $('.filter-options .form-check').show();
        loadCourses();
      });

      // Filter search input inside accordions
      $(document).on('input', '.filter-search', function() {
        const searchTerm = $(this).val().toLowerCase();
        $(this).siblings('.filter-options').find('.form-check').each(function() {
          const label = $(this).text().toLowerCase();
          $(this).toggle(label.includes(searchTerm));
        });
      });

      // Global search input
      $('#searchInput').on('input', function() {
        loadCourses();
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>