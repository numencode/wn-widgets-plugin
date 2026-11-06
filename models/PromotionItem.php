<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * An item of a promotion group.
 */
class PromotionItem extends WidgetItem
{
    public $table = 'numencode_widgets_promotions_items';

    public $belongsTo = [
        'group' => [PromotionGroup::class, 'key' => 'group_id'],
    ];
}
