<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\PromotionGroup;
use NumenCode\Widgets\Models\PromotionItem;

class Promotions extends ComponentBase
{
    /**
     * @var PromotionGroup The promotion group model used for displaying promotion items.
     */
    public $promotion;

    public function componentDetails()
    {
        return [
            'name'        => 'numencode.widgets::lang.promotions.name',
            'description' => 'numencode.widgets::lang.promotions.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'       => 'numencode.widgets::lang.promotions.group_title',
                'description' => 'numencode.widgets::lang.promotions.group_description',
                'type'        => 'dropdown',
                'default'     => 'default',
            ],
            'layout' => [
                'title'       => 'numencode.widgets::lang.promotions.layout_title',
                'description' => 'numencode.widgets::lang.promotions.layout_description',
                'type'        => 'dropdown',
                'default'     => 'bootstrap4',
            ],
        ];
    }

    public function getTitleOptions()
    {
        return PromotionGroup::lists('title', 'id');
    }

    public function getLayoutOptions()
    {
        return [
            'bootstrap4' => 'Bootstrap 4',
            'bootstrap3' => 'Bootstrap 3',
        ];
    }

    public function onRun()
    {
        $this->promotion = $this->loadPromotion();
    }

    public function onRender()
    {
        if (!$layout = $this->property('layout')) {
            $layout = 'bootstrap4';
        }

        return $this->renderPartial('@' . $layout . '.htm');
    }

    protected function loadPromotion()
    {
        return PromotionGroup::find($this->property('title'));
    }
}
