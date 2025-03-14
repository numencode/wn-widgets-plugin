<?php namespace NumenCode\Widgets\Models;

use Model;
use Winter\Storm\Database\Traits\Sortable;
use Winter\Storm\Database\Traits\Validation;
use NumenCode\Fundamentals\Traits\Publishable;

class GalleryItem extends Model
{
    use Publishable, Sortable, Validation;

    public $table = 'numencode_widgets_gallery_items';

    public $implement = ['@Winter.Translate.Behaviors.TranslatableModel'];

    public $translatable = [
        'title',
        'subtitle',
    ];

    public $rules = [];

    protected $fillable = [
        'title',
        'subtitle',
        'picture',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public $belongsTo = [
        'group' => [GalleryGroup::class, 'key' => 'group_id'],
    ];
}
