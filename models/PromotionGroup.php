<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * A group of promotion items.
 */
class PromotionGroup extends WidgetGroup
{
    public $table = 'numencode_widgets_promotions_groups';

    public $hasMany = [
        'items' => [PromotionItem::class, 'key' => 'group_id', 'delete' => true],
    ];
}
