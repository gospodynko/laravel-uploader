@extends('layouts.app')

@section('title', 'List')

@section('content')
    <div class="card">
        <div class="card-header">Downloaded list</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>File name</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($files as $file)
                    <tr id="row-{{ $file->id }}">
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->filename }}</td>
                        <td>{{ $file->created_at }}</td>
                        <td>
                            <button class="btn btn-danger delete-btn" data-id="{{ $file->id }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.delete-btn').click(function () {
                let fileId = $(this).data('id');

                if (!confirm("Remove file?")) return;

                $.ajax({
                    url: "/uploads/" + fileId,
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        $("#row-" + fileId).remove();
                    },
                    error: function () {
                        alert("Error.");
                    }
                });
            });
        });
    </script>
@endsection
