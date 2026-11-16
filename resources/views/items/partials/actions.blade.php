<div class="w-100 d-flex flex-wrap gap-1">
    <button type="button"
        class="btn btn-warning btn-sm flex-grow-1"
        data-url="{{ route('items.show', Crypt::encrypt($item->id)) }}"
        onclick="openEditModal(this)">
        Edit
    </button>
    <button type="button"
        class="btn btn-danger btn-sm flex-grow-1"
        data-url="{{ route('items.destroy', Crypt::encrypt($item->id)) }}"
        data-name="{{ $item->product_name }}"
        onclick="openDeleteModal(this)">
        Hapus
    </button>
</div>