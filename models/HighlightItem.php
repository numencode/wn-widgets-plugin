<?php namespace NumenCode\Widgets\Models;

use Model;
use Winter\Storm\Database\Traits\Sortable;
use Winter\Storm\Database\Traits\Validation;
use NumenCode\Fundamentals\Traits\Publishable;

class HighlightItem extends Model
{
    use Publishable, Sortable, Validation;

    public $table = 'numencode_widgets_highlights_items';

    public $implement = ['@Winter.Translate.Behaviors.TranslatableModel'];

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

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public $belongsTo = [
        'group' => [HighlightGroup::class, 'key' => 'group_id'],
    ];
}
