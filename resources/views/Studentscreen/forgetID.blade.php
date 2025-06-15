@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="mb-3">Forgot Your Unique Student ID?</h4>
        <div id="responseMessage"></div>

        <form id="findUniqueIdForm">
            @csrf
            <div class="form-group mb-3">
                <label for="aadhar_no">Enter Your Aadhar Number</label>
                <input type="text" name="aadhar_no" id="aadhar_no" class="form-control" placeholder="Enter Aadhar Number" required>
            </div>
            <button type="submit" class="btn btn-primary">Find My ID</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#findUniqueIdForm').on('submit', function(e) {
        e.preventDefault();
        let aadhar = $('#aadhar_no').val();

        $.ajax({
            url: '/FindUniqueId',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                aadhar_no: aadhar
            },
            success: function(response) {
                $('#responseMessage').html(`<div class="alert alert-success">Your Unique Student ID is: <strong>${response.unique_sid}</strong></div>`);
            },
            error: function() {
                $('#responseMessage').html(`<div class="alert alert-danger">Student not found with this Aadhar number.</div>`);
            }
        });
    });
</script>
@endsection
