@extends('layouts.app')

@section('title', 'File download')

@section('content')
    <div class="card">
        <div class="card-header">File download</div>
        <div class="card-body">
            <form id="uploadForm">
                <div class="mb-3">
                    <label class="form-label">Choose file</label>
                    <input type="file" id="fileInput" accept=".docx, .pdf" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Download</button>
            </form>
            <div id="uploadStatus" class="mt-3"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#uploadForm').submit(function (e) {
                e.preventDefault();
                let file = $('#fileInput')[0].files[0];

                if (!file) {
                    alert("Choose file!");
                    return;
                }

                let formData = new FormData();
                formData.append('file', file);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: "{{ route('uploads.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#uploadStatus').html('<div class="alert alert-success">File downloaded!</div>');
                        $('#fileInput').val('');
                    },
                    error: function () {
                        $('#uploadStatus').html('<div class="alert alert-danger">download error.</div>');
                    }
                });
            });
        });
    </script>
@endsection
