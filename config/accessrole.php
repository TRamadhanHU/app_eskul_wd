<?php

return [
    1 => [
        'name' => 'Administator',
        'permissions' => [
            'dashboard', 
            'master',
            'anggota', 'anggota_manage', 'anggota_cetak',
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
            'anggota', 'anggota_cetak',
            'absensi', 'absensi_cetak',
            'dokumentasi', 'dokumentasi_cetak'
        ]
    ],
    3 => [
        'name' => 'Operator',
        'permissions' => [
            'dashboard', 
            'master',
            'users', 'users_manage', 'users_cetak',
            'anggota', 'anggota_manage', 'anggota_cetak',
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