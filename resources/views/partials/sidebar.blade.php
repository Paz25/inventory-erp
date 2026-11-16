<aside class="sidebar p-4 d-flex flex-column">
    <h1 class="mb-3 fs-4 fw-bold">
        Inventory ERP
    </h1>
    <div>
        @auth
        @foreach ($menus as $menu)
        <a href="{{ route($menu['route']) }}"
            class="{{ request()->routeIs($menu['active']) ? 'active' : '' }}">
            {{ $menu['label'] }}
        </a>
        @endforeach
        @endauth
    </div>

    @auth
    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
        <div class="mb-2">
            <div class="fw-semibold text-truncate">{{ auth()->user()->username }}</div>
            <small class="text-secondary">{{ auth()->user()->role_name }}</small>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
    @endauth

    @guest
    <div class="mt-auto pt-3 border-top">
        <a href="{{ route('login') }}" class="btn btn-primary w-100 text-white">Masuk</a>
    </div>
    @endguest
</aside>