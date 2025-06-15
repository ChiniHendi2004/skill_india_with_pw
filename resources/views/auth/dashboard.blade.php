@extends('layouts.app')

@section('pagetitle')
User Dashboard
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-10">
                    <h3 class="m-0">Dashboard</h3>
                </div><!-- /.col -->
                <div class="col-sm-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Demo Page</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class=" px-3">
        <div class="card" style="height: 69vh;">
            <div class="text-center" style="margin-top: 220px;">
                <h2>Welcome To Dashboard</h2>
            </div>
            <form id="logoutForm">
                @csrf
                <button type="submit">Logout</button>
            </form>

        </div>
    </div>

</div>
<!-- /.content-wrapper -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        let token = localStorage.getItem('auth_token');

        if (!token) {
            alert('Unauthorized! Redirecting to login.');
            window.location.href = '/login';
        }

        $('#logoutForm').submit(function(e) {
            e.preventDefault();

            let token = localStorage.getItem('auth_token');

            $.ajax({
                url: '/logout',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    localStorage.removeItem('auth_token');
                    alert('Logout successful!');
                    window.location.href = '/login';
                },
                error: function() {
                    alert('Logout failed. Try again.');
                }
            });
        });

    });
</script>
@endsection

@section('styles')
<style scoped>
    .nav-tabs .nav-link.active,
    .nav-tabs .show>.nav-link {
        background-color: #cad0d6
    }

    .nav-tabs .nav-link:not(.active):hover {
        border: 1px solid #cad0d6;
    }
</style>

@endsection