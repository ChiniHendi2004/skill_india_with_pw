@extends('layouts.app')

@section('pagetitle')
Group Details || Create
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0" style="color: black;">Create Group Details</h2>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Group Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div id="responseMessage"></div>

        <div class="row px-2 mt-3">
            <!-- Left: Form -->
            <div class="col-lg-6">
                <div class="card p-3">
                    <form method="POST" id="myForm">
                        @csrf
                        <input type="hidden" id="g_id" name="g_id" value="{{ $g_id }}">
                        <div class="mb-3">
                            <label for="value" class="form-label">Group</label>
                            <input type="text" class="form-control" id="group_name" name="value" placeholder="Enter Group Value" disabled>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Table -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body table-responsive p-3" style="height: 538px">
                        <table class="table table-bordered table-hover text-nowrap" id="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be loaded via AJAX -->
                            </tbody>
                        </table>
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
        const g_id = $('#g_id').val();
        groupName(g_id);
        fetchDataAndPopulateTable(g_id);
    });

    function groupName(g_id) {
        if (!g_id) {
            alert('Invalid group ID');
            return;
        }

        $.ajax({
            url: `/View/Groups/Details/${g_id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#group_name').val(response.name);
            },
            error: function() {
                alert('Error fetching group name.');
            }
        });
    }

    function fetchDataAndPopulateTable(g_id) {
        $.ajax({
            url: `/Group/Details/List/${g_id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let tbody = $('#data-table tbody');
                tbody.empty(); // Clear old rows
                let counter = 1;
                $.each(response, function(index, item) {
                    let row = `<tr><td>${counter}</td><td>${item.value}</td></tr>`;
                    tbody.append(row);
                    counter++;
                });
            },
            error: function() {
                alert('Error fetching group details.');
            }
        });
    }
</script>
@endsection
