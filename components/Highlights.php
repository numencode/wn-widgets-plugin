<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\HighlightGroup;

class Highlights extends ComponentBase
{
    public $group;

    public function componentDetails(): array
    {
        return [
            'name'        => 'numencode.widgets::lang.highlights.name',
            'description' => 'numencode.widgets::lang.highlights.description',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'title' => [
                'title'       => 'numencode.widgets::lang.highlights.group_title',
                'description' => 'numencode.widgets::lang.highlights.group_description',
                'type'        => 'dropdown',
                'default'     => 'default',
            ],
            'layout' => [
                'title'       => 'numencode.widgets::lang.highlights.layout_title',
                'description' => 'numencode.widgets::lang.highlights.layout_description',
                'type'        => 'dropdown',
                'default'     => 'default',
            ],
        ];
    }

    public function onRun()
    {
        $this->group = $this->loadGroup();
    }

    public function onRender()
    {
        if (!$layout = $this->property('layout')) {
            $layout = 'default';
        }

        return $this->renderPartial('@' . $layout . '.htm');
    }

    public function getTitleOptions(): array
    {
        return HighlightGroup::lists('title', 'id');
    }

    public function getLayoutOptions(): array
    {
        return [
            'default' => 'Default',
        ];
    }

    protected function loadGroup()
    {
        return HighlightGroup::find($this->property('title'));
    }
}
