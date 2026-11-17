<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory ERP')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/3.1.5/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            height: 100dvh;
            width: 250px;
            flex-shrink: 0;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="app-layout d-flex">
        @include('partials.sidebar')

        <div class="main-content flex-grow-1"">
            <main class=" page-content p-4">
            @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/3.1.5/js/dataTables.js"></script>
    @stack('scripts')
</body>

</html>