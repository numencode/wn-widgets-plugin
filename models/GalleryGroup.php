<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * A group of gallery items.
 */
class GalleryGroup extends WidgetGroup
{
    public $table = 'numencode_widgets_gallery_groups';

    public $hasMany = [
        'items' => [GalleryItem::class, 'key' => 'group_id', 'delete' => true],
    ];
}
