<?php namespace NumenCode\Widgets\Models;

use Model;
use October\Rain\Database\Traits\Validation;

class PromotionItem extends Model
{
    use Validation;

    public $table = 'numencode_widgets_promotions_items';

    public $rules = [];
}
