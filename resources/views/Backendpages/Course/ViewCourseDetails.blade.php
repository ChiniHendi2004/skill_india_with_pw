@extends('layouts.app')

@section('pagetitle')
Course | Details
@endsection

@section('content')
<div class="container py-4">
  <div class="card shadow-sm border-0">
    <div id="responseMessage" class="p-3"></div>

    <div class="card-body">
      <div class="row g-4 align-items-center">
        
        <!-- Course Info -->
        <div class="col-md-7">
          <h3 id="course-name" class="mb-3 text-primary fw-bold"></h3>
          <p id="description" class="text-muted mb-4" style="min-height: 80px;"></p>

          <div class="mb-3">
            <span class="fw-semibold">Fee:</span> ₹<span id="fees" class="text-dark">0</span>
          </div>
          <div class="mb-3">
            <span class="fw-semibold">Duration:</span> <span id="duration" class="text-dark">0</span>
          </div>

          <div class="mb-4">
            <span class="fw-semibold">Terms:</span>
            <div id="terms" class="mt-2 ps-3 border-start border-3 border-primary">
              <!-- Terms will appear here -->
            </div>
          </div>

          <div>
            <span class="fw-semibold">Categories:</span>
            <div id="groups" class="mt-2">
              <!-- Groups will appear here -->
            </div>
          </div>
        </div>

        <!-- Course Image -->
        <div class="col-md-5 text-center">
          <img id="course-image" src="" class="img-fluid rounded shadow-sm border" alt="Course Image" style="max-height: 280px; object-fit: contain;">
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Hidden Input -->
<input type="hidden" id="courseId" value="{{ $id }}">
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    const courseId = $('#courseId').val();

    if (!courseId) {
      $('#responseMessage').html(`<div class="alert alert-warning">Course ID is missing.</div>`);
      return;
    }

    $.ajax({
      url: '/Get/Course/Details/' + courseId,
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(res) {
        const data = res;

        $('#course-name').text(data.course_name);
        $('#description').text(data.course_details.description);
        $('#fees').text(data.course_details.fee ?? 'N/A');
        $('#duration').text(data.formatted_duration);
        $('#course-image').attr('src', data.course_image || '/placeholder.png');

        // Terms
        const termsHtml = (Array.isArray(data.terms) && data.terms.length)
          ? data.terms.map(term => `<p>• ${term}</p>`).join('')
          : `<p>No terms available.</p>`;
        $('#terms').html(termsHtml);

        // Groups
        if (Array.isArray(data.groups) && data.groups.length) {
          const grouped = {};
          data.groups.forEach(item => {
            grouped[item.group_name] = grouped[item.group_name] || [];
            grouped[item.group_name].push(item.group_value);
          });

          let groupsHtml = '';
          for (const group in grouped) {
            groupsHtml += `<div class="mb-2"><strong>${group}:</strong> ${grouped[group].join(', ')}</div>`;
          }
          $('#groups').html(groupsHtml);
        } else {
          $('#groups').html('<p>No categories assigned.</p>');
        }
      },
      error: function() {
        $('#responseMessage').html('<div class="alert alert-danger">Failed to load course details.</div>');
      }
    });
  });
</script>
@endsection

@section('style')
<style>
  .card {
    border-radius: 1rem;
  }

  #terms p {
    margin-bottom: 0.4rem;
    font-size: 14px;
  }

  #groups div {
    font-size: 14px;
  }
</style>
@endsection
