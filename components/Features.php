<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\FeatureGroup;
use NumenCode\Fundamentals\Traits\ComponentRenderer;

class Features extends ComponentBase
{
    use ComponentRenderer;

    public $group;

    public function componentDetails(): array
    {
        return [
            'name'        => 'numencode.widgets::lang.features.name',
            'description' => 'numencode.widgets::lang.features.description',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'title' => [
                'title'       => 'numencode.widgets::lang.features.group_title',
                'description' => 'numencode.widgets::lang.features.group_description',
                'type'        => 'dropdown',
                'default'     => 'default',
            ],
            'layout' => [
                'title'       => 'numencode.widgets::lang.features.layout_title',
                'description' => 'numencode.widgets::lang.features.layout_description',
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
        return FeatureGroup::lists('title', 'id');
    }

    protected function loadGroup()
    {
        return FeatureGroup::find($this->property('title'));
    }
}
