<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\GalleryGroup;

class Gallery extends ComponentBase
{
    public $gallery;
    public $itemsPerRow;

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
            'title'         => [
                'title'       => 'numencode.widgets::lang.gallery.gallery_title',
                'description' => 'numencode.widgets::lang.gallery.gallery_description',
                'type'        => 'dropdown',
                'default'     => 'default',
            ],
            'layout'        => [
                'title'       => 'numencode.widgets::lang.gallery.layout_title',
                'description' => 'numencode.widgets::lang.gallery.layout_description',
                'type'        => 'dropdown',
                'default'     => 'default',
            ],
            'items_per_row' => [
                'title'             => 'numencode.widgets::lang.gallery.items_per_row',
                'type'              => 'string',
                'default'           => '4',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Value can contain only numeric symbols',
            ],
        ];
    }

    public function onRun()
    {
        $this->gallery = $this->loadGallery();
        $this->itemsPerRow = $this->property('items_per_row') ?? 4;
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
        ];
    }

    protected function loadGallery()
    {
        return GalleryGroup::find($this->property('title'));
    }
}
