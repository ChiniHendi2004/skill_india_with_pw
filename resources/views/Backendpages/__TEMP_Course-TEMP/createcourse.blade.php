@extends('layouts.app')

@section('pagetitle')
Create Course
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold">Create Course</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Create Course</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->


  <!-- Main content -->
  <div class="content px-4">
    <div id="responseMessage"></div>
    <div class="row">
      <div class="col-lg-4  ">
        <div class="">

          <div class="card ">
            <!-- /.card-header -->
            <!-- form start -->
            <form id="Course_form" enctype="multipart/form-data">
              @csrf

              <div class="card-body p-3">

                <div class="mb-2">
                  <label for="Course_group_id" class="form-label">Course Type</label>
                  <select class="form-control" name="cg_id" id="Course_group_id">
                    <option value="" selected disabled>Select Course</option>
                  </select>
                </div>

                <div>
                  <div class="mb-3">
                    <label for="sds" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="sds" placeholder="Enter Course Type Name" name="name" value="{{ old('Course_name') }}">
                  </div>
                </div>

                <div>
                  <div class="mb-3">
                    <label for="customUrl" class="form-label">Image Thumbnail URL</label>
                    <input class="form-control" type="url" id="customUrl" name="image" placeholder="Enter Thumbnail URL" value="{{ old('image') }}">
                    <div id="imagePreview" class="mt-2"></div>
                  </div>
                </div>

                <div>
                  <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                  </div>
                </div>

                <div class="">
                  <button type="submit" class="btn text-white submitCourse" style="background-color: #00008B">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>


      </div>
      <div class="col-lg-8 ">
        <div class="card align-middle">
          <p class="d-flex justify-content-center font-weight-bold pt-2">Course Section</p>
        </div>
        <div class="row table-head-fixed" id="cardContainer" style=" height:550px ; overflow: overlay;">


        </div>
      </div>
    </div>

  </div>
</div>



<div class="modal fade" id="myModaldel" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5 class="text-center">Are you sure you want to delete?</h5>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn text-white" id="delete-confirm-button" style="background-color: #eb0d1c">Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



@section('scripts')
<script>
  $(document).ready(function() {
    list = '';
    Dropdownlist = $('#Course_group_id')
    $.ajax({
      type: "GET",
      url: `/Course/Groups/Active/List`,
      dataType: "json",
      success: function(response) {

        list = response
        for (let i = 0; i < response.length; i++) {
          list = response[i];
          Dropdownlist.append('<option value="' + list.id + '">' + list.name + '</option>');
        }
      }
    });
  });




  // Delete button click
  $(document).on('click', '.deletebtn', function() {
    const id = $(this).data('course-id'); // ✅ Use lowercase and match attribute exactly
    $('#id').val(id); // Set hidden input if needed
    $('#delete-confirm-button').data('course-id', id); // ✅ Pass ID to confirm button
    $('#myModaldel').modal('show');
  });

  // Confirm Delete
  $('#myModaldel').on('click', '#delete-confirm-button', function() {
    var id = $(this).data('course-id');

    $.ajax({
      type: 'DELETE',
      url: `/Delete/Course/${id}`,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      success: function(response) {
        $('#myModaldel').modal('hide');
        $('#responseMessage').html(`<div class="alert alert-success">${response.message}</div>`);
        Courselist();
      },
      error: function() {
        alert('Failed to delete group.');
      }
    });
  });
</script>

<script>
  // Get Dropdown List Data 
  $(document).ready(function() {
    Courselist()
  });


  function Courselist() {

    var Course_list = ""
    var cardContainer = $('#cardContainer');
    cardContainer.empty();
    $.ajax({
      type: "GET",
      // url: "/Course/List",
      url: `/Course/List`,
      success: function(response) {

        for (let i = 0; i < response.data.length; i++) {
          Course_list = response.data[i];
          console.log();

          var Course_name = Course_list.name
          var dynamicImageUrl = Course_list.image
          var card = `
             <div class="col-lg-6 col-md-6 ">
              <div class="card ">
                <div class="d-flex">
                  <div class="p-2">
                    <img src="${dynamicImageUrl}" class="rounded "  style="border:none; background-color: transparent ; box-shadow: none ; width: 100%; height:200px ">

                    <span class="d-flex justify-content-center">
                      <a href="{{ url('/Add/Course/Details/Page/${Course_list.id}') }}">
                        <h4 class="font-weight-bold my-2" id="Course_name">${Course_name}</h4>
                      </a>
                    </span>
                  </div>
                  <div class="pr-2 py-1 dropleft">
                    
                    <a href="javascript:;"  class="" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v " style="font-size: 0.8rem; color: #090b0f;"></i></a>
                    <div class="dropdown-menu ">
                      
                      <a class="dropdown-item" href="{{ url('/Edit/Course/Page/${Course_list.id}') }}"><i class="fas fa-edit mr-2" style="color: #2d6bd7;"></i>edit</a>
                      <a class="dropdown-item" href="{{ url('/Viwe/Course/Details/Page/${Course_list.id}') }}"><i class="far fa-eye mr-2" style="color: #047c32;"></i>view</a>
                      <a class="dropdown-item deletebtn" href="#" data-Course-id="${Course_list.id}"><i class="fas fa-trash-alt mr-2" style="color: #e01f45;"></i>Delete</a>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
                        
          `;
          cardContainer.append(card);

        }
      }
    });
  }





  // Course Insert 

  $(document).ready(function() {
    $("#Course_form").submit(function(event) {
      event.preventDefault();
      var formData = new FormData(this);
      var form = this;
      $.ajax({
        type: "POST",
        url: `/Create/Course`,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          form.reset();
          $('#responseMessage').html('<div class="alert alert-success">' + response.message +
            '</div>');
          Courselist()

        },
        error: function(error) {

          console.error(error);
        },
      });
    });
  });
</script>


@endsection

@endsection