<?php

return [
    [
        'name' => 'Dashboard',
        'url' => 'dashboard',
        'icon' => 'fas fa-home',
        'code' => 'dashboard',
    ],
    [
        'name' => 'Master',
        'url' => '',
        'icon' => 'fa fa-archive',
        'code' => 'master',
        'child' => [
            [
                'name' => 'Users',
                'url' => 'master/users',
                'code' => 'users'
            ],
            [
                'name' => 'Eskul',
                'url' => 'master/eskul',
                'code' => 'eskul'
            ],
            [
                'name' => 'Jadwal',
                'url' => 'master/jadwal',
                'code' => 'jadwal'
            ],
        ]

    ],
    [
        'name' => 'Anggota',
        'url' => 'anggota',
        'icon' => 'fas fa-users',
        'code' => 'anggota',
    ],
    [
        'name' => 'Absensi',
        'url' => 'list-absensi',
        'icon' => 'fas fa-book-reader',
        'code' => 'absensi',
    ],
    [
        'name' => 'Dokumentasi',
        'url' => 'list-dokumentasi',
        'icon' => 'fas fa-camera',
        'code' => 'dokumentasi',
    ],
];