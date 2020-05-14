<?php namespace NumenCode\Widgets\Models;

use Model;
use October\Rain\Database\Traits\SoftDelete;
use October\Rain\Database\Traits\Validation;

class PromotionGroup extends Model
{
    use SoftDelete, Validation;

    protected $dates = ['deleted_at'];

    public $table = 'numencode_widgets_promotions_groups';

    public $rules = [];
}
