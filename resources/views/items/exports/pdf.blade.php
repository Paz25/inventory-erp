<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang</title>

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
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
    <h2>Daftar Barang</h2>
    <div class="meta">
        Dicetak: {{ $printedAt->format('d/m/Y H:i') }}
        @if ($search)
        &middot; Filter: "{{ $search }}"
        @endif
        &middot; Total: {{ $items->count() }} barang
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 18%">Kode</th>
                <th>Nama Barang</th>
                <th style="width: 20%">Kategori</th>
                <th style="width: 10%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
            <tr>
                <td class="num">{{ $loop->iteration }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->product_category }}</td>
                <td class="num">{{ number_format($item->quantity, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>