<?php

return [
    'plugin'      => [
        'name'        => 'Widgets',
        'description' => 'Widgets plugin for October CMS'
    ],
    'permissions' => [
        'promotions' => 'Manage promotions',
    ],
    'promotions'  => [
        'name'               => 'Promotions',
        'description'        => 'Create and manage various promotions for the website',
        'layout_title'       => 'Layout',
        'layout_description' => 'Change layout for the promotions display',
        'group_title'        => 'Promotion',
        'group_description'  => 'Displayed group of promotions',
    ],
];
