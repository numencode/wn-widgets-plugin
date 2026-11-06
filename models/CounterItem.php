<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * A counter: a number (e.g. 500) with an optional suffix (e.g. "+"), a title and an icon CSS class.
 */
class CounterItem extends WidgetItem
{
    public $table = 'numencode_widgets_counters_items';

    public $translatable = [
        'title',
        'suffix',
    ];

    public $rules = [
        'value' => 'required|numeric',
    ];

    protected $fillable = [
        'title',
        'icon',
        'value',
        'suffix',
        'is_published',
    ];

    public $belongsTo = [
        'group' => [CounterGroup::class, 'key' => 'group_id'],
    ];
}
