<aside id="sidebar" class="sidebar">
  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('DashboardPage')}}" class="logo d-flex align-items-center text-center">
      <span class="d-none d-lg-block text-white">LOGO</span>
    </a>
  </div>
  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link" href="{{ route('DashboardPage')}}" class="{{ in_array(Route::currentRouteName(), ['DashboardPage']) ? 'nav-link active ' : 'nav-link' }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>


    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#organization-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Organization</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="organization-nav" class="nav-content collapse " data-bs-parent="organization-nav">

        <li>
          <a href="/Manage-College/info/Page">
            <i class="bi bi-circle"></i><span>Add Organization</span>
          </a>

        </li>

        <li>
          <a href="/Manage-College/List/Page">
            <i class="bi bi-circle"></i><span>Manage Organization</span>
          </a>

        </li>

      </ul>

    </li>

    <P class="sidebar-section">MASTER</P>
    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#masters-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Masters</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="masters-nav" class="nav-content collapse " data-bs-parent="masters-nav">

        <li>
          <a href="/Course-Master">
            <i class="bi bi-server"></i><span>Course</span>
          </a>

        </li>

        <li>
          <a href="/Department-Master">
            <i class="bi bi-circle"></i><span> Department Master</span>
          </a>

        </li>


        <li>
          <a href="/Updates-Master">
            <i class="bi bi-newspaper"></i><span>Update Master</span>
          </a>

        </li>

        <li>
          <a href="/Designation-Master">
            <i class="bi bi-circle"></i><span>Designation Master</span>
          </a>

        </li>

        <li>
          <a href="/Download-Master">
            <i class="bi bi-download"></i><span>Download Master</span>
          </a>

        </li>

        <li>
          <a href="/AlbumMaster">
            <i class="bi bi-file-earmark-image"></i><span>Album Master</span>
          </a>

        </li>

        <li>
          <a href="/Manage-FAQ">
            <i class="bi bi-circle"></i><span> FAQ Master</span>
          </a>

        </li>

      </ul>

    </li>

    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#employee-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Employee</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="employee-nav" class="nav-content collapse " data-bs-parent="employee-nav">

        <li>
          <a href="/Add/Employee/Page">
            <i class="bi bi-server"></i><span>Add Employee</span>
          </a>

        </li>

        <li>
          <a href="/Add/Employee/Contact/Page/6763d5ab32fde">
            <i class="bi bi-circle"></i><span> Employee Contact</span>
          </a>

        </li>


        <li>
          <a href="/Add/Employee/Address/Page/6762df079dc09">
            <i class="bi bi-newspaper"></i><span>Employee Address</span>
          </a>

        </li>

        <li>
          <a href="/Add/Employee/Education/Page">
            <i class="bi bi-circle"></i><span>Employee Education Info</span>
          </a>

        </li>

        <li>
          <a href="/Employee/Experience/Page">
            <i class="bi bi-download"></i><span>Employee Experience</span>
          </a>

        </li>

        <li>
          <a href="/Add/Employee/Role/Page">
            <i class="bi bi-file-earmark-image"></i><span>Employee Role</span>
          </a>

        </li>

        <li>
          <a href="/Employee/List/Page">
            <i class="bi bi-circle"></i><span> Employee List</span>
          </a>

        </li>

        <li>
          <a href="/Employee/Details/Page">
            <i class="bi bi-circle"></i><span> Employee Details</span>
          </a>

        </li>

      </ul>

    </li>

    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#nalbum-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Album</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="nalbum-nav" class="nav-content collapse " data-bs-parent="nalbum-nav">


        <li>
          <a href="/AlbumPage">
            <i class="bi bi-file-earmark-image"></i><span>Add Album </span>
          </a>

        </li>

        <li>
          <a href="/AlbumImagePage">
            <i class="bi bi-circle"></i><span> Add Album Image </span>
          </a>

        </li>

      </ul>

    </li>

    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#vacancy-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Vacancy</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="vacancy-nav" class="nav-content collapse " data-bs-parent="vacancy-nav">


        <li>
          <a href="/Vacancie/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Vacancy </span>
          </a>

        </li>
        <li>
          <a href="/Vacancie/List/Page">
            <i class="bi bi-file-earmark-image"></i><span> Vacancy List</span>
          </a>

        </li>
        <li>
          <a href="/Applications/Page">
            <i class="bi bi-file-earmark-image"></i><span>Applications </span>
          </a>

        </li>
      </ul>

    </li>


    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#updates-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>updates</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="updates-nav" class="nav-content collapse " data-bs-parent="updates-nav">


        <li>
          <a href="/Create/Updates/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add updates </span>
          </a>

        </li>

        <li>
          <a href="/Updates/List/Page">
            <i class="bi bi-file-earmark-image"></i><span>Updates List </span>
          </a>

        </li>


      </ul>

    </li>



    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#activity-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Activity</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="activity-nav" class="nav-content collapse " data-bs-parent="activity-nav">


        <li>
          <a href="/Create/ActivityGroup/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Activity Group </span>
          </a>

        </li>

        <li>
          <a href="/Create/Activity/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Activity </span>
          </a>

        </li>

        <li>
          <a href="/Activity/List/Page">
            <i class="bi bi-file-earmark-image"></i><span>Activity List </span>
          </a>
        </li>
      </ul>
    </li>


    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#committee-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Committee</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="committee-nav" class="nav-content collapse " data-bs-parent="committee-nav">


        <li>
          <a href="/Create/Committee/Group/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add committee Group </span>
          </a>

        </li>

        <li>
          <a href="/Create/Committee/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add committee </span>
          </a>

        </li>

        <li>
          <a href="/Committee/List/Page">
            <i class="bi bi-file-earmark-image"></i><span>committee List </span>
          </a>

        </li>


      </ul>

    </li>

   


    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#Portfolio-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Portfolio</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="Portfolio-nav" class="nav-content collapse " data-bs-parent="Portfolio-nav">


        <li>
          <a href="/Create/Portfolio/Group/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Portfolio Group </span>
          </a>

        </li>

        <li>
          <a href="/Create/Portfolio/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Portfolio </span>
          </a>

        </li>

        <li>
          <a href="/Portfolio/List/Page">
            <i class="bi bi-file-earmark-image"></i><span>Portfolio List </span>
          </a>

        </li>


      </ul>
      
    </li>


    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#Download-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Download</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="Download-nav" class="nav-content collapse " data-bs-parent="Download-nav">


        <li>
          <a href="/Create/Download/Group/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Download Group </span>
          </a>

        </li>

        <li>
          <a href="/Create/Download/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Download </span>
          </a>

        </li>

        <li>
          <a href="/Download/List/Page">
            <i class="bi bi-file-earmark-image"></i><span>Download List </span>
          </a>

        </li>


      </ul>
      
    </li>



    <li class="nav-item ">
      <a class="nav-link collapsed" data-bs-target="#Image-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Image</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>

      <ul id="Image-nav" class="nav-content collapse " data-bs-parent="Image-nav">


        <li>
          <a href="/Create/Image/Group/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Image Group </span>
          </a>

        </li>

        <li>
          <a href="/Create/Image/Page">
            <i class="bi bi-file-earmark-image"></i><span>Add Image </span>
          </a>

        </li>

        <li>
          <a href="/Image/List/Page">
            <i class="bi bi-file-earmark-image"></i><span>Image List </span>
          </a>

        </li>


      </ul>
      
    </li>






















  </ul>


</aside>