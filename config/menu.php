<?php

return [
    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'active' => 'dashboard',
        'roles' => null,
    ],
    [
        'label' => 'Manajemen Barang',
        'route' => 'items',
        'active' => 'items*',
        'roles' => null,
    ],
    [
        'label' => 'Manajemen Supplier',
        'route' => 'suppliers',
        'active' => 'suppliers*',
        'roles' => ['admin'],
    ],
    [
        'label' => 'Transaksi Stok',
        'route' => 'stock-movements',
        'active' => 'stock-movements*',
        'roles' => null,
    ],
];
