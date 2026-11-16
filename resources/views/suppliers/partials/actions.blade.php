<div class="w-100 d-flex flex-wrap gap-1">
    <button
        type="button"
        class="btn btn-warning btn-sm flex-grow-1"
        data-url="{{ route('suppliers.show', Crypt::encrypt($supplier->id)) }}"
        onclick="openEditSupplierModal(this)">
        Edit
    </button>
    <button
        type="button"
        class="btn btn-danger btn-sm flex-grow-1"
        data-url="{{ route('suppliers.destroy', Crypt::encrypt($supplier->id)) }}"
        data-name="{{ $supplier->supplier_name }}"
        onclick="openDeleteSupplierModal(this)">
        Hapus
    </button>
</div>