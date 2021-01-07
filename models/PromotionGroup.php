<?php namespace NumenCode\Widgets\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;
use NumenCode\Fundamentals\Traits\Publishable;

class PromotionGroup extends Model
{
    use Publishable, SoftDelete, Validation;

    public $table = 'numencode_widgets_promotions_groups';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
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

    protected $dates = [
        'deleted_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public $relationable = [
        'items_list' => 'items',
    ];

    public $hasMany = [
        'items' => [PromotionItem::class, 'key' => 'group_id', 'delete' => true],
    ];

    public function getItemCountAttribute()
    {
        return count($this->items);
    }
}
