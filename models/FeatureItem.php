<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * An item of a feature group.
 */
class FeatureItem extends WidgetItem
{
    public $table = 'numencode_widgets_features_items';

    public $belongsTo = [
        'group' => [FeatureGroup::class, 'key' => 'group_id'],
    ];
}
