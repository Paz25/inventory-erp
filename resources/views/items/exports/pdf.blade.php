@extends('exports.layout')

@section('thead')
<tr>
    <th style="width: 5%">No</th>
    <th style="width: 18%">Kode</th>
    <th>Nama Barang</th>
    <th style="width: 20%">Kategori</th>
    <th style="width: 10%">Jumlah</th>
</tr>
@endsection

@section('tbody')
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
@endsection