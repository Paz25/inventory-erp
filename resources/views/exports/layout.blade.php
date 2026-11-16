<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 11px;
        }

        h2 {
            margin: 0 0 4px;
        }

        .meta {
            color: #555;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 5px 6px;
        }

        th {
            background: #e9ecef;
            text-align: left;
        }

        td.num {
            text-align: right;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <h2>{{ $title }}</h2>
    <div class="meta">
        Dicetak: {{ $printedAt->format('d/m/Y H:i') }}
        @if ($search)
        &middot; Filter: "{{ $search }}"
        @endif
        &middot; Total: {{ $total }} data
    </div>

    <table>
        <thead>
            <tr>@yield('thead')</tr>
        </thead>
        <tbody>
            @yield('tbody')
        </tbody>
    </table>
</body>

</html>