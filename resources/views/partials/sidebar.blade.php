<aside class="sidebar p-4">
    <h1 class="mb-3 fs-4 fw-bold">
        Inventory ERP
    </h1>
    <a href="{{ route('dashboard') }}"
        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('items') }}" class="{{ request()->routeIs('items') ? 'active' : '' }}">Manajemen Barang</a>
    <a href="#">Manajemen Supplier</a>
</aside>