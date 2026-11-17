@extends('layouts.index')

@section('content')
<div class="">
    <h3 class="fw-bold font-black mb-1">Manajemen User</h3>
    <div class="my-3 border-bottom border-secondary-subtle w-100"></div>
</div>
<div class="d-flex justify-content-between align-items-center">
    <div class="card shadow-sm w-100">
        <div class="card-body">
            <div class="alert-body">
                @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center gap-2">
                <form id="searchForm"
                    action="{{ route('users') }}"
                    method="GET"
                    class="d-flex justify-content-between gap-2 w-100">
                    <input id="searchFormInput"
                        type="text"
                        name="search"
                        class="form-control w-100"
                        placeholder="Cari username atau role..."
                        value="{{ $search ?? ''}}">
                    <button type="submit" class="btn btn-info">
                        Search
                    </button>
                </form>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Export
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item js-export-link"
                                href="{{ route('items.export.excel', request()->only('search')) }}"
                                data-base-url="{{ route('items.export.excel') }}">
                                Excel (.xlsx)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item js-export-link"
                                href="{{ route('items.export.pdf', request()->only('search')) }}"
                                data-base-url="{{ route('items.export.pdf') }}">
                                PDF
                            </a>
                        </li>
                    </ul>
                </div>

                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#userModal"
                    style="width: 20%"
                    onclick="openCreateUserModal()">
                    Create User
                </button>
            </div>

            <div id="itemTable" class="mt-3">
                @include('users.partials.table')
            </div>
        </div>
    </div>
</div>

@include('users.partials.create-edit-modal')

<script>
    function showAlert(type, message) {
        const wrapper = document.createElement('div');
        wrapper.className = `alert alert-${type} alert-dismissible fade show`;
        wrapper.setAttribute('role', 'alert');

        const text = document.createElement('span');
        text.textContent = message;

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'btn-close';
        close.setAttribute('data-bs-dismiss', 'alert');
        close.setAttribute('aria-label', 'Close');

        wrapper.append(text, close);
        document.querySelector('.alert-body').replaceChildren(wrapper);
    }

    function openCreateUserModal() {
        const userForm = document.getElementById('userForm');

        userForm.reset();
        userForm.action = "{{ route('users.store') }}";
        document.getElementById('userModalLabel').textContent = 'Create User';
        document.getElementById('userSubmitButton').textContent = 'Save User';

    }
</script>

@endsection