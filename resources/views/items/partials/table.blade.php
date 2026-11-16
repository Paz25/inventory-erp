<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>kategori</th>
                <th>Stok</th>
                <th>Status</th>

                @role('admin')
                <th>Aksi</th>
                @endrole
            </tr>
        </thead>

        <tbody>
            @forelse ($items as $item)
            <tr>
                <td>{{ $items->firstItem() + $loop->index }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->product_category }}</td>
                <td>{{ $item->quantity }}</td>
                <td>
                    @if ($item->quantity > 0)
                    <span class="badge bg-success">Tersedia</span>
                    @else
                    <span class="badge bg-danger">Habis</span>
                    @endif
                </td>
                @role('admin')
                <td>
                    <div class="w-100 d-flex flex-wrap gap-1">
                        <button
                            type="button"
                            class="btn btn-warning btn-sm flex-grow-1"
                            data-url="{{ route('items.show', Crypt::encrypt($item->id)) }}"
                            onclick="openEditModal(this)">
                            Edit
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm flex-grow-1"
                            data-url="{{ route('items.destroy', Crypt::encrypt($item->id)) }}"
                            data-name="{{ $item->product_name }}"
                            onclick="openDeleteModal(this)">
                            Hapus
                        </button>
                    </div>
                </td>
                @endrole
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">
                    Belum ada data barang.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>