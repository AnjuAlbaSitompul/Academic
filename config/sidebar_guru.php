<?php

return [
    [
        'name' => 'Main',
        'items' => [
            [
                'name' => 'Dashboard',
                'url' => '/dashboard',
                'icon' => 'fe fe-home',
                'submenu' => []
            ]
        ]
    ],
    [
        'name' => 'Master',
        'items' => [
            [
                'name' => 'Siswa',
                'url' => '',
                'icon' => 'fe fe-users',
                'submenu' => [
                    [
                        'name' => 'Data Siswa',
                        'url' => '/siswa',
                    ]
                ]
            ],
        ]
    ],
    [
        'name' => 'Reports',
        'items' => [
            [
                'name' => 'Nilai Siswa',
                'url' => '',
                'icon' => 'fe fe-bar-chart-2',
                'submenu' => [
                    [
                        'name' => 'Data Nilai Siswa',
                        'url' => '/nilai-siswa',
                    ]
                ]
            ],
            [
                'name' => 'Laporan',
                'url' => '/laporan',
                'icon' => 'fe fe-file-text',
                'submenu' => []
            ]
        ]
    ]
];
