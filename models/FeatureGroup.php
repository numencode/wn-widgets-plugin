<?php namespace NumenCode\Widgets\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;
use NumenCode\Fundamentals\Traits\Publishable;

class FeatureGroup extends Model
{
    use Publishable, SoftDelete, Validation;

    public $table = 'numencode_widgets_features_groups';

    public $implement = [
        '@RainLab.Translate.Behaviors.TranslatableModel',
        '@NumenCode.Fundamentals.Behaviors.Relationable',
    ];

    public $translatable = ['title'];

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

    public $hasMany = [
        'items' => [FeatureItem::class, 'key' => 'group_id', 'delete' => true],
    ];

    public function getItemCountAttribute()
    {
        return count($this->items);
    }

    public static function boot()
    {
        static::extend(function ($model) {
            $model->addDynamicProperty('relationable', ['items_list' => 'items']);
        });
    }
}
