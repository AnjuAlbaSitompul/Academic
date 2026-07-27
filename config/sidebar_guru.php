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
                    ],
                    [
                        'name' => 'Data Non-Akademik',
                        'url' => '/nilai-non-akademik',
                    ]
                ]
            ],
            [
                'name' => 'Evaluasi Model',
                'url' => '/evaluasi',
                'icon' => 'fe fe-activity',
                'submenu' => []
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
