<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

