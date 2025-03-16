<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;
use NumenCode\Widgets\Models\GalleryGroup;
use NumenCode\Fundamentals\Traits\ComponentRenderer;

class Gallery extends ComponentBase
{
    use ComponentRenderer;

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

    public function getTitleOptions(): array
    {
        return GalleryGroup::lists('title', 'id');
    }

    protected function loadGallery()
    {
        return GalleryGroup::find($this->property('title'));
    }
}
