@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row">
    <!-- Filter Sidebar -->
    <div class="col-md-3">
      <h5 class="mb-3">Filters</h5>
      <div class="accordion" id="filterAccordion">
        <form id="filterForm">
          @foreach ($groups as $index => $group)
          <div class="accordion-item mb-2">
            <h2 class="accordion-header" id="heading{{ $index }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                aria-controls="collapse{{ $index }}">
                {{ $group->name }}
              </button>
            </h2>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse"
              aria-labelledby="heading{{ $index }}" data-bs-parent="#filterAccordion">
              <div class="accordion-body">
                <input type="text" class="form-control mb-2 filter-search" placeholder="Search...">
                <div class="filter-options">
                  @foreach ($group->group_details as $detail)
                  <div class="form-check">
                    <input class="form-check-input filter-checkbox"
                      type="checkbox"
                      name="filters[{{ $group->id }}][]"
                      value="{{ $detail->id }}"
                      id="filter_{{ $detail->id }}">
                    <label class="form-check-label" for="filter_{{ $detail->id }}">
                      {{ $detail->value }}
                    </label>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </form>
        <button type="button" id="resetFilters" class="btn btn-outline-primary mt-3 w-100">Reset Filters</button>
      </div>
    </div>

    <!-- Courses List -->
    <div class="col-md-9">
      <div class="row" id="coursesList">
        @foreach ($courses as $course)
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            <img src="{{ $course->image }}">
            <div class="card-body">
              <h5 class="card-title">{{ $course->name }}</h5>
              <p class="card-text text-muted small">{{ $course->description ?? 'No description available' }}</p>
              <a href="#" class="btn btn-primary w-100">Apply</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Load courses based on filters
    function loadCourses() {
      let formData = $('#filterForm').serialize();
      $.ajax({
        url: '/courses/filter',
        method: 'GET',
        data: formData,
        success: function(response) {
          renderCourses(response);
          console.log(response);

        },
        error: function() {
          alert('Failed to load courses. Please try again.');
        }
      });
    }

    // Render courses dynamically
    function renderCourses(courses) {
      let html = '';
      if (courses.length > 0) {
        courses.forEach(course => {
          html += `
        <div class="col-md-4 mb-4">
          <div class="card h-100 shadow-sm">
            <img src="${course.image}" class="card-img-top" alt="${course.name}">
            <div class="card-body">
              <h5 class="card-title">${course.name}</h5>
              <p class="card-text text-muted small">${course.description ?? 'No description available'}</p>
              <a href="#" class="btn btn-primary w-100">Apply</a>
            </div>
          </div>
        </div>
      `;
        });
      } else {
        html = '<div class="col-12 text-center text-muted">No courses found.</div>';
      }
      $('#coursesList').html(html);
    }


    // Trigger filter when checkboxes are changed
    $(document).on('change', '.filter-checkbox', function() {
      loadCourses();
    });

    // Reset filters
    $('#resetFilters').click(function() {
      // Reset form
      $('#filterForm')[0].reset();

      // Clear search fields
      $('.filter-search').val('');

      // Show all filter options (if hidden by search)
      $('.filter-options .form-check').show();

      // Reload all courses
      loadCourses();
    });

    // Search inside filter accordion options
    $(document).on('input', '.filter-search', function() {
      const searchTerm = $(this).val().toLowerCase();
      $(this).siblings('.filter-options').find('.form-check').each(function() {
        const label = $(this).text().toLowerCase();
        $(this).toggle(label.includes(searchTerm));
      });
    });
  });
</script>
@endsection