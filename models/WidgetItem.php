<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

use Model;
use NumenCode\Fundamentals\Traits\Publishable;
use Winter\Storm\Database\Traits\Sortable;
use Winter\Storm\Database\Traits\Validation;

/**
 * Base model of a widget item (e.g. one slide or one gallery picture). Items are ordered (Sortable)
 * and can be unpublished.
 *
 * A widget type defines its table and the "group" relation:
 *
 *     class PromotionItem extends WidgetItem
 *     {
 *         public $table = 'numencode_widgets_promotions_items';
 *
 *         public $belongsTo = [
 *             'group' => [PromotionGroup::class, 'key' => 'group_id'],
 *         ];
 *     }
 */
abstract class WidgetItem extends Model
{
    use Publishable;
    use Sortable;
    use Validation;

    public $implement = [
        '@Winter.Translate.Behaviors.TranslatableModel',
    ];

    public $translatable = [
        'title',
        'subtitle',
        'content',
        'link',
        'link_title',
    ];

    public $rules = [];

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'link',
        'link_title',
        'picture',
        'is_published',
    ];

    /**
     * New records are published by default.
     */
    public $attributes = [
        'is_published' => true,
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
