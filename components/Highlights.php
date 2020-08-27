<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\HighlightGroup;

class Highlights extends ComponentBase
{
    /**
     * @var HighlighGroup The highlight group model used for displaying highlight items.
     */
    public $highlight;

    public function componentDetails()
    {
        return [
            'name'        => 'numencode.widgets::lang.highlights.name',
            'description' => 'numencode.widgets::lang.highlights.description',
        ];
    }

    public function defineProperties()
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

    public function getTitleOptions()
    {
        return HighlightGroup::lists('title', 'id');
    }

    public function getLayoutOptions()
    {
        return [
            'default' => 'Default',
            'media'   => 'Default with pictures',
        ];
    }

    public function onRun()
    {
        $this->highlight = $this->loadHighlight();
    }

    public function onRender()
    {
        if (!$layout = $this->property('layout')) {
            $layout = 'default';
        }

        return $this->renderPartial('@' . $layout . '.htm');
    }

    protected function loadHighlight()
    {
        return HighlightGroup::find($this->property('title'));
    }
}
