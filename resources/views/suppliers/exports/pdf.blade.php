@extends('exports.layout')

@section('thead')
<th style="width: 4%">No</th>
<th style="width: 18%">Nama Perusahaan</th>
<th>Alamat</th>
<th style="width: 15%">Nama PIC</th>
<th style="width: 14%">No. Telp PIC</th>
<th style="width: 18%">Email PIC</th>
@endsection

@section('tbody')
@forelse ($suppliers as $supplier)
<tr>
    <td class="num">{{ $loop->iteration }}</td>
    <td>{{ $supplier->supplier_name }}</td>
    <td>{{ $supplier->supplier_address }}</td>
    <td>{{ $supplier->pic_name }}</td>
    <td>{{ $supplier->pic_contact_number }}</td>
    <td>{{ $supplier->pic_contact_email }}</td>
</tr>
@empty
<tr>
    <td colspan="6" style="text-align:center">Tidak ada data.</td>
</tr>
@endforelse
@endsection