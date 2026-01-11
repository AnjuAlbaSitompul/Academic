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
        'name' => 'Akademik',
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
            ]
        ]
    ]
];
