<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * A group of highlight items.
 */
class HighlightGroup extends WidgetGroup
{
    public $table = 'numencode_widgets_highlights_groups';

    public $hasMany = [
        'items' => [HighlightItem::class, 'key' => 'group_id', 'delete' => true],
    ];
}
