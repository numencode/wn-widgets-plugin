<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

/**
 * An item of a gallery group.
 */
class GalleryItem extends WidgetItem
{
    public $table = 'numencode_widgets_gallery_items';

    public $translatable = [
        'title',
        'subtitle',
    ];

    protected $fillable = [
        'title',
        'subtitle',
        'picture',
        'is_published',
    ];

    public $belongsTo = [
        'group' => [GalleryGroup::class, 'key' => 'group_id'],
    ];
}
