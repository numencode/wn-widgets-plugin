<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * A group of counters.
 */
class CounterGroup extends WidgetGroup
{
    public $table = 'numencode_widgets_counters_groups';

    public $hasMany = [
        'items' => [CounterItem::class, 'key' => 'group_id', 'delete' => true],
    ];
}
