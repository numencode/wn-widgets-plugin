<?php

/*
 * Field labels (title, content, link, published, ...) come from NumenCode.Fundamentals:
 * numencode.fundamentals::lang.field.*, item.* and timestamp.*
 */

return [
    'plugin'      => [
        'name'        => 'Widgets',
        'description' => 'Reusable content widgets: promotions, features, highlights, galleries and counters.',
    ],
    'permissions' => [
        'promotions' => 'Manage promotions',
        'features'   => 'Manage features',
        'highlights' => 'Manage highlights',
        'gallery'    => 'Manage galleries',
        'counters'   => 'Manage counters',
    ],
    'component'   => [
        'group'              => 'Group',
        'group_description'  => 'The displayed group',
        'group_placeholder'  => '– select –',
        'group_required'     => 'Select a group.',
        'layout'             => 'Layout',
        'layout_description' => 'Layout used to display the component',
    ],
    'promotions'  => [
        'name'        => 'Promotions',
        'description' => 'Displays a group of promotions, e.g. as a slider.',
        'record'      => 'Promotion',
        'manage'      => 'Manage promotions',
        'create'      => 'Create promotion',
        'update'      => 'Edit promotion',
        'preview'     => 'Preview promotion',
    ],
    'features'    => [
        'name'        => 'Features',
        'description' => 'Displays a group of features.',
        'record'      => 'Feature',
        'manage'      => 'Manage features',
        'create'      => 'Create feature',
        'update'      => 'Edit feature',
        'preview'     => 'Preview feature',
    ],
    'highlights'  => [
        'name'        => 'Highlights',
        'description' => 'Displays a group of highlights.',
        'record'      => 'Highlight',
        'manage'      => 'Manage highlights',
        'create'      => 'Create highlight',
        'update'      => 'Edit highlight',
        'preview'     => 'Preview highlight',
    ],
    'gallery'     => [
        'name'          => 'Gallery',
        'description'   => 'Displays a picture gallery.',
        'record'        => 'Gallery',
        'manage'        => 'Manage galleries',
        'create'        => 'Create gallery',
        'update'        => 'Edit gallery',
        'preview'       => 'Preview gallery',
        'items_per_row' => 'Items per row',
    ],
    'counters'    => [
        'name'           => 'Counters',
        'description'    => 'Displays a group of counters (numbers with a title and an icon).',
        'record'         => 'Counters',
        'manage'         => 'Manage counters',
        'create'         => 'Create counters',
        'update'         => 'Edit counters',
        'preview'        => 'Preview counters',
        'icon'           => 'Icon',
        'icon_comment'   => 'CSS class of the icon, e.g. "icon-star".',
        'value'          => 'Value',
        'suffix'         => 'Suffix',
        'suffix_comment' => 'Shown after the number, e.g. "+" or "%".',
    ],
];
