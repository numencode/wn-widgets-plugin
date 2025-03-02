<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\GalleryGroup;

class Gallery extends ComponentBase
{
    public $group;

    public function componentDetails(): array
    {
        return [
            'name'        => 'numencode.widgets::lang.gallery.name',
            'description' => 'numencode.widgets::lang.gallery.description',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'title' => [
                'title'       => 'numencode.widgets::lang.gallery.group_title',
                'description' => 'numencode.widgets::lang.gallery.group_description',
                'type'        => 'dropdown',
                'default'     => 'default',
            ],
            'layout' => [
                'title'       => 'numencode.widgets::lang.gallery.layout_title',
                'description' => 'numencode.widgets::lang.gallery.layout_description',
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
        return GalleryGroup::lists('title', 'id');
    }

    public function getLayoutOptions(): array
    {
        return [
            'default' => 'Default',
            'media'   => 'Default with pictures',
        ];
    }

    protected function loadGroup()
    {
        return GalleryGroup::find($this->property('title'));
    }
}
