<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * An item of a highlight group.
 */
class HighlightItem extends WidgetItem
{
    public $table = 'numencode_widgets_highlights_items';

    public $belongsTo = [
        'group' => [HighlightGroup::class, 'key' => 'group_id'],
    ];
}
