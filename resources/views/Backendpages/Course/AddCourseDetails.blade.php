@extends('layouts.app')

@section('pagetitle')
Add / Edit Course Details
@endsection

@section('content')

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">Add / Edit Course Details</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Add / Edit Course Details</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content px-4">
    <div id="responseMessage"></div>

    <div class="row">
      <!-- Left: Course Info -->
      <div class="col-lg-5 mb-4">
        <div class="card shadow">
          <div class="card-header font-weight-bold">Course Info</div>
          <div class="card-body">
            <form id="Course_form" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="c_id" value="{{ $id }}">

              <div class="mb-3">
                <label class="form-label">Course Name</label>
                <input type="text" class="form-control" value="{{ $course->name }}" disabled>
              </div>

              <div class="mb-3">
                <label class="form-label">Course Fee</label>
                <div class="d-flex align-items-center gap-3">
                  <!-- Free Checkbox -->
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="isFreeCheckbox">
                    <label class="form-check-label" for="isFreeCheckbox">
                      Free
                    </label>
                  </div>

                  <!-- Fee Input -->
                  <input class="form-control" type="text" id="feeInput" name="fee" placeholder="Enter Course Fee" style="max-width: 200px;">
                </div>
              </div>


              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4" placeholder="Write a brief description..."></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Duration</label>
                <div class="row g-2">
                  <div class="col">
                    <input type="number" class="form-control" name="years" min="0" placeholder="Year">
                  </div>
                  <div class="col">
                    <input type="number" class="form-control" name="months" min="0" placeholder="Month">
                  </div>
                  <div class="col">
                    <input type="number" class="form-control" name="days" min="0" placeholder="Day">
                  </div>
                  <div class="col">
                    <input type="number" class="form-control" name="hours" min="0" placeholder="Hour">
                  </div>
                  <div class="col">
                    <input type="number" class="form-control" name="minutes" min="0" placeholder="Min">
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100 mt-3">Submit</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Right: Groups and Group Details -->
      <div class="col-lg-7 mb-4">
        <div class="row">
          <!-- Groups List -->
          <div class="col-md-5">
            <div class="card shadow">
              <div class="card-header font-weight-bold">Groups</div>
              <div class="card-body p-2" style="max-height:495px; overflow-y: auto;">
                <ul class="list-group" id="groupList">
                  @foreach($groups as $group)
                  <li class="list-group-item list-group-item-action group-item" data-gid="{{ $group->id }}">
                    {{ $group->name }}
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>

          <!-- Group Details -->
          <div class="col-md-7">
            <div class="card shadow" style="height: 550px;">
              <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <span id="groupTitle">Select a Group</span>
              </div>
              <div class="card-body" style="max-height: 450px; overflow: auto;">
                <div id="selectedTags" style="height: 80px; width: 340px; overflow-y: auto; overflow-x: hidden;" class="mb-3"></div>

                <div class="mb-2">
                  <input type="text" id="searchBox" class="form-control form-control-sm" placeholder="Search..." disabled>
                </div>

                <div id="groupDetailsContainer">
                  <div class="text-muted text-center">Please select a group from the left.</div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>

    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Setup CSRF token for all AJAX POST requests
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    let selectedGroupDetails = {}; // { g_id: [detail_id, ...] }
    let groupDetailsCache = {}; // { g_id: { detail_id: label, ... }, ... }

    // Load existing course details and selected group details via AJAX
    function loadExistingDetails() {
      let c_id = $('input[name=c_id]').val();
      if (!c_id) return;

      $.ajax({
        url: '/Get/Course/Details/' + c_id,
        type: 'GET',
        success: function(res) {
          if (res.course_details) {
            // Fill description textarea
            $('textarea[name=description]').val(res.course_details.description || '');

            // Convert total minutes duration back to years, months, days, hours, minutes inputs
            if (res.course_details.duration) {
              let totalMins = parseInt(res.course_details.duration);
              if (isNaN(totalMins)) totalMins = 0;

              let years = Math.floor(totalMins / (60 * 24 * 365));
              totalMins -= years * 60 * 24 * 365;

              let months = Math.floor(totalMins / (60 * 24 * 30));
              totalMins -= months * 60 * 24 * 30;

              let days = Math.floor(totalMins / (60 * 24));
              totalMins -= days * 60 * 24;

              let hours = Math.floor(totalMins / 60);
              totalMins -= hours * 60;

              let minutes = totalMins;

              $('input[name=years]').val(years);
              $('input[name=months]').val(months);
              $('input[name=days]').val(days);
              $('input[name=hours]').val(hours);
              $('input[name=minutes]').val(minutes);
            }


            let fee = res.course_details.fee || '';
            if (fee.toLowerCase() === 'free' || fee.trim() === '') {
              $('#isFreeCheckbox').prop('checked', true);
              $('#feeInput').val('Free').prop('readonly', true).addClass('bg-light');
            } else {
              $('#isFreeCheckbox').prop('checked', false);
              $('#feeInput').val(fee).prop('readonly', false).removeClass('bg-light');
            }
          }

          if (res.groups && res.groups.length > 0) {
            // Build selectedGroupDetails from response
            selectedGroupDetails = {};
            res.groups.forEach(gd => {
              if (!selectedGroupDetails[gd.group_id]) {
                selectedGroupDetails[gd.group_id] = [];
              }
              if (!selectedGroupDetails[gd.group_id].includes(gd.group_detail_id)) {
                selectedGroupDetails[gd.group_id].push(gd.group_detail_id);
              }
            });

            // Load group details for all selected groups to cache labels
            loadAllGroupDetailsForSelected();
          }
        },
        error: function() {
          console.log('Failed to load course details');
        }
      });
    }

    // Load group details for all groups currently selected to fill groupDetailsCache
    function loadAllGroupDetailsForSelected() {
      const groupIds = Object.keys(selectedGroupDetails);

      if (groupIds.length === 0) {
        refreshSelectedTags(); // Nothing selected, just clear tags
        return;
      }

      let loadedCount = 0;
      groupIds.forEach(g_id => {
        $.ajax({
          url: '/getGroupDetails/' + g_id,
          type: 'GET',
          success: function(response) {
            groupDetailsCache[g_id] = {};
            response.forEach(item => {
              groupDetailsCache[g_id][item.id] = item.value;
            });

            loadedCount++;
            if (loadedCount === groupIds.length) {
              // After all loaded, refresh tags
              refreshSelectedTags();
            }
          },
          error: function() {
            // Even on error, count and refresh after all attempts
            loadedCount++;
            if (loadedCount === groupIds.length) {
              refreshSelectedTags();
            }
          }
        });
      });
    }

    // Refresh tags UI from selectedGroupDetails using cached labels
    function refreshSelectedTags() {
      $('#selectedTags').empty();

      for (const [g_id, details] of Object.entries(selectedGroupDetails)) {
        details.forEach(detail_id => {
          // Use cached label if available, fallback to 'Selected'
          let label = (groupDetailsCache[g_id] && groupDetailsCache[g_id][detail_id]) || 'Selected';

          // Append tag only if not already present
          if ($(`.selected-tag[data-id=${detail_id}]`).length === 0) {
            $('#selectedTags').append(`
              <span class="badge bg-primary text-white me-1 mb-1 selected-tag" data-gid="${g_id}" data-id="${detail_id}">
                ${label} <span class="ms-1" style="cursor:pointer;" onclick="removeTag(${g_id}, ${detail_id})">&times;</span>
              </span>
            `);
          }
        });
      }
    }

    // When user clicks a group, load group details and check existing selections
    $('.group-item').on('click', function() {
      $('.group-item').removeClass('active');
      $(this).addClass('active');

      let g_id = $(this).data('gid');
      let groupName = $(this).text();
      $('#groupTitle').text(groupName);
      $('#searchBox').prop('disabled', false).val('');

      let container = $('#groupDetailsContainer');
      container.html('<div class="text-center text-muted">Loading...</div>');

      $.ajax({
        url: '/getGroupDetails/' + g_id,
        type: 'GET',
        success: function(response) {
          container.empty();

          // Cache group details for label lookup
          groupDetailsCache[g_id] = {};

          if (response.length > 0) {
            response.forEach(item => {
              groupDetailsCache[g_id][item.id] = item.value;

              let isChecked = selectedGroupDetails[g_id] && selectedGroupDetails[g_id].includes(item.id);
              container.append(`
                <div class="form-check mb-2 group-value" data-label="${item.value.toLowerCase()}">
                  <input type="checkbox" class="form-check-input group-detail-checkbox" 
                         data-gid="${g_id}" value="${item.id}" id="gd_${item.id}"
                         ${isChecked ? 'checked' : ''}>
                  <label class="form-check-label" for="gd_${item.id}">${item.value}</label>
                </div>
              `);
            });
          } else {
            container.html('<div class="text-muted text-center">No details available for this group.</div>');
          }
        },
        error: function() {
          container.html('<div class="text-danger text-center">Failed to load group details.</div>');
        }
      });
    });

    // Filter group details on search input
    $('#searchBox').on('input', function() {
      let query = $(this).val().toLowerCase();
      $('.group-value').each(function() {
        let label = $(this).data('label');
        $(this).toggle(label.indexOf(query) !== -1);
      });
    });

    // Handle checkbox change: add/remove from selectedGroupDetails and refresh tags
    $(document).on('change', '.group-detail-checkbox', function() {
      let g_id = $(this).data('gid');
      let val = parseInt($(this).val());
      if ($(this).is(':checked')) {
        if (!selectedGroupDetails[g_id]) selectedGroupDetails[g_id] = [];
        if (!selectedGroupDetails[g_id].includes(val)) selectedGroupDetails[g_id].push(val);
      } else {
        if (selectedGroupDetails[g_id]) {
          selectedGroupDetails[g_id] = selectedGroupDetails[g_id].filter(v => v !== val);
          if (selectedGroupDetails[g_id].length === 0) delete selectedGroupDetails[g_id];
        }
      }
      refreshSelectedTags();
    });

    // Remove tag by clicking 'x'
    window.removeTag = function(g_id, detail_id) {
      if (selectedGroupDetails[g_id]) {
        selectedGroupDetails[g_id] = selectedGroupDetails[g_id].filter(v => v !== detail_id);
        if (selectedGroupDetails[g_id].length === 0) delete selectedGroupDetails[g_id];
      }
      // Uncheck checkbox if visible
      $(`.group-detail-checkbox[data-gid="${g_id}"][value="${detail_id}"]`).prop('checked', false);
      refreshSelectedTags();
    };

    // Form submission
    $('#Course_form').on('submit', function(e) {
      e.preventDefault();

      if (Object.keys(selectedGroupDetails).length === 0) {
        alert('Please select at least one group detail.');
        return;
      }

      let formData = $(this).serializeArray();

      let years = parseInt($('input[name=years]').val()) || 0;
      let months = parseInt($('input[name=months]').val()) || 0;
      let days = parseInt($('input[name=days]').val()) || 0;
      let hours = parseInt($('input[name=hours]').val()) || 0;
      let minutes = parseInt($('input[name=minutes]').val()) || 0;

      let totalMinutes =
        years * 365 * 24 * 60 +
        months * 30 * 24 * 60 +
        days * 24 * 60 +
        hours * 60 +
        minutes;

      formData.push({
        name: 'duration',
        value: totalMinutes
      });

      // Convert selectedGroupDetails object to array of { g_id, details }
      const selectedGroupsArray = Object.entries(selectedGroupDetails).map(([g_id, details]) => ({
        g_id: parseInt(g_id),
        details: details.map(d => parseInt(d))
      }));
      formData.push({
        name: 'selectedGroupDetails',
        value: JSON.stringify(selectedGroupsArray)
      });

      $.ajax({
        url: '/storeCourseDetails',
        type: 'POST',
        data: formData,
        success: function(response) {
          $('#responseMessage').html('<div class="alert alert-success">Course details saved successfully!</div>');

          // Reset only selections and UI, keep image and description intact
          selectedGroupDetails = {};
          groupDetailsCache = {};
          $('#selectedTags').empty();
          $('#groupDetailsContainer').html('<div class="text-muted text-center">Please select a group from the left.</div>');
          $('#groupTitle').text('Select a Group');
          $('#searchBox').val('').prop('disabled', true);
          $('.group-item').removeClass('active');
          loadExistingDetails();
        },
        error: function(xhr) {
          let errMsg = 'Failed to save course details.';
          if (xhr.responseJSON && xhr.responseJSON.error) errMsg = xhr.responseJSON.error;
          $('#responseMessage').html(`<div class="alert alert-danger">${errMsg}</div>`);
        }
      });
    });

    // Initial load of existing data
    loadExistingDetails();

    // Handle isFree checkbox toggle
    $('#isFreeCheckbox').change(function() {
      if ($(this).is(':checked')) {
        $('#feeInput').val('Free').prop('readonly', true).addClass('bg-light');
      } else {
        $('#feeInput').val('').prop('readonly', false).removeClass('bg-light');
      }
    });
  });
</script>
@endsection