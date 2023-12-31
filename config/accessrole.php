<?php

return [
    1 => [
        'name' => 'Administator',
        'permissions' => [
            'dashboard', 
            'users', 'users_manage', 'users_cetak',
            'jadwal', 'jadwal_manage', 'jadwal_cetak',
            'eskul', 'eskul_manage', 'eskul_cetak',
            'absensi', 'absensi_cetak',
            'dokumentasi', 'dokumentasi_cetak', 'dokumentasi_manage',
        ]
    ],
    2 => [
        'name' => 'Pembina',
        'permissions' => [
            'dashboard',
            'users', 'users_cetak',
            'absensi', 'absensi_cetak',
            'dokumentasi', 'dokumentasi_cetak'
        ]
    ],
    3 => [
        'name' => 'Operator',
        'permissions' => [
            'dashboard', 
            'jadwal', 'jadwal_manage',
            'absensi', 'absensi_cetak', 
            'dokumentasi', 'dokumentasi_cetak', 'dokumentasi_manage',
        ]
    ],
    4 => [
        'name' => 'Anggota',
        'permissions' => [
            'anggota',
        ]
    ],
];