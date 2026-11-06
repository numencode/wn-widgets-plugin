<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * A group of feature items.
 */
class FeatureGroup extends WidgetGroup
{
    public $table = 'numencode_widgets_features_groups';

    public $hasMany = [
        'items' => [FeatureItem::class, 'key' => 'group_id', 'delete' => true],
    ];
}
