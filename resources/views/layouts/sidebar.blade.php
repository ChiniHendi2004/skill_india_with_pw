<aside id="sidebar" class="sidebar">
  <!-- Logo Section -->
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('StartPage') }}" class="logo d-flex align-items-center text-center">
      <span class="d-none d-lg-block text-white">Atreya Webs</span>
    </a>
  </div>


  <!-- Sidebar Navigation -->
  <nav>
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a href="{{ route('StartPage') }}"
          class="nav-link {{ Route::currentRouteName() === 'StartPage' ? 'active' : 'collapsed' }}">
          <i class="bi bi-house-door"></i>
          <span>Dashboard</span>
        </a>
      </li>


      <li class="mb-1">
        <a href="#updates-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CreateUpdatesPage', 'UpdateListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#updates-nav">
          <i class="bi bi-newspaper"></i>
          <span>Courses</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="updates-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CoursePage','CourseListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CoursePage') }}"
              class="{{ Route::currentRouteName() === 'CoursePage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Add Course </span>
            </a>
          </li>

          <li class="mb-1">
            <a href="{{ route('CourseListPage') }}"
              class="{{ Route::currentRouteName() === 'CourseListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span> Course List </span>
            </a>
          </li>



        </ul>
      </li>

      <li class="mb-1">
        <a href="#course-group-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['CourseGroupPage', 'CourseGroupListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#course-group-nav">
          <i class="bi bi-newspaper"></i>
          <span>Course Group</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="course-group-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['CourseGroupPage','CourseGroupListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('CourseGroupPage') }}"
              class="{{ Route::currentRouteName() === 'CourseGroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Add Course Group </span>
            </a>
          </li>

          <li class="mb-1">
            <a href="{{ route('CourseGroupListPage') }}"
              class="{{ Route::currentRouteName() === 'CourseGroupListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span> Course Group List </span>
            </a>
          </li>



        </ul>
      </li>



      <li class="mb-1">
        <a href="#group-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['GroupPage', 'GroupListPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#group-nav">
          <i class="bi bi-newspaper"></i>
          <span>Master Groups</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="group-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['GroupPage','GroupListPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('GroupPage') }}"
              class="{{ Route::currentRouteName() === 'GroupPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Add Group </span>
            </a>
          </li>

          <li class="mb-1">
            <a href="{{ route('GroupListPage') }}"
              class="{{ Route::currentRouteName() === 'GroupListPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Group List </span>
            </a>
          </li>



        </ul>
      </li>




      <li class="mb-1">
        <a href="#application-nav"
          class="nav-link {{ in_array(Route::currentRouteName(), ['PendingApplicationsPage', 'RejectedApplicationsPage','ApprovedApplicationsPage']) ? 'active' : 'collapsed' }}"
          data-bs-toggle="collapse"
          data-bs-target="#application-nav">
          <i class="bi bi-newspaper"></i>
          <span>Applications</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="application-nav"
          class="nav-content collapse {{ in_array(Route::currentRouteName(), ['PendingApplicationsPage','RejectedApplicationsPage','ApprovedApplicationsPage']) ? 'show' : '' }}">
          <li class="mb-1">
            <a href="{{ route('PendingApplicationsPage') }}"
              class="{{ Route::currentRouteName() === 'PendingApplicationsPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Pending Applications </span>
            </a>
          </li>

          <li class="mb-1">
            <a href="{{ route('RejectedApplicationsPage') }}"
              class="{{ Route::currentRouteName() === 'RejectedApplicationsPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Rejected Applications </span>
            </a>
          </li>

          <li class="mb-1">
            <a href="{{ route('ApprovedApplicationsPage') }}"
              class="{{ Route::currentRouteName() === 'ApprovedApplicationsPage' ? 'active' : '' }}">
              <i class="bi bi-circle"></i>
              <span>Accepted Applications </span>
            </a>
          </li>



        </ul>
      </li>






    </ul>


  </nav>
</aside>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(el) {
      el.addEventListener('click', function(e) {
        e.preventDefault(); // Stop default anchor jump
        const target = el.getAttribute('data-bs-target');
        const collapseEl = document.querySelector(target);
        const bsCollapse = new bootstrap.Collapse(collapseEl, {
          toggle: true
        });
      });
    });
  });
</script>