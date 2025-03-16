<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\PromotionGroup;
use NumenCode\Fundamentals\Traits\ComponentRenderer;

class Promotions extends ComponentBase
{
    use ComponentRenderer;

    public $group;

    public function componentDetails(): array
    {
        return [
            'name'        => 'numencode.widgets::lang.promotions.name',
            'description' => 'numencode.widgets::lang.promotions.description',
        ];
    }

    public function defineProperties(): array
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
                'default'     => 'default',
            ],
        ];
    }

    public function onRun()
    {
        $this->group = $this->loadGroup();
    }

    public function getTitleOptions(): array
    {
        return PromotionGroup::lists('title', 'id');
    }

    protected function loadGroup()
    {
        return PromotionGroup::find($this->property('title'));
    }
}
