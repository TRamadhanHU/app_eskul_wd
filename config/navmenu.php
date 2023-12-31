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
                'name' => 'Jadwal',
                'url' => 'master/jadwal',
                'code' => 'jadwal'
            ],
            [
                'name' => 'Eskul',
                'url' => 'master/eskul',
                'code' => 'eskul'
            ]
        ]

    ],
    [
        'name' => 'Absensi',
        'url' => 'absensi',
        'icon' => 'fas fa-book-reader',
        'code' => 'absensi',
    ],
    [
        'name' => 'Dokumentasi',
        'url' => 'dokumentasi',
        'icon' => 'fas fa-camera',
        'code' => 'dokumentasi',
    ],
];