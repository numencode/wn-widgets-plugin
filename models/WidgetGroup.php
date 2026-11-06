<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Models;

use Model;
use NumenCode\Fundamentals\Traits\Publishable;
use Winter\Storm\Database\Traits\Validation;

/**
 * Base model of a widget group (e.g. a promotion slider or a gallery) with its items.
 *
 * A widget type defines its table and the "items" relation:
 *
 *     class PromotionGroup extends WidgetGroup
 *     {
 *         public $table = 'numencode_widgets_promotions_groups';
 *
 *         public $hasMany = [
 *             'items' => [PromotionItem::class, 'key' => 'group_id', 'delete' => true],
 *         ];
 *     }
 *
 * The items are edited inline in the group form (relationrepeater field "items_list").
 */
abstract class WidgetGroup extends Model
{
    use Publishable;
    use Validation;

    public $implement = [
        '@Winter.Translate.Behaviors.TranslatableModel',
        'NumenCode.Fundamentals.Behaviors.RelationableModel',
    ];

    public $translatable = [
        'title',
        'content',
        'link',
        'link_title',
    ];

    public $rules = [
        'title' => 'required',
    ];

    protected $fillable = [
        'title',
        'content',
        'link',
        'link_title',
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

    /**
     * Form field "items_list" edits the "items" relation.
     */
    public $relationable = [
        'items_list' => 'items',
    ];

    /**
     * Options for the component's group dropdown: all groups (also unpublished ones) by title.
     *
     * @return array<int|string, string>
     */
    public static function getGroupOptions(): array
    {
        return static::withUnpublished()->orderBy('title')->lists('title', 'id');
    }
}
