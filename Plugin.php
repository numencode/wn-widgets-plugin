<?php namespace NumenCode\Widgets;

use System\Classes\PluginBase;
use NumenCode\Widgets\Components\Counters;
use NumenCode\Widgets\Components\Features;
use NumenCode\Widgets\Components\Highlights;
use NumenCode\Widgets\Components\Promotions;
use NumenCode\Fundamentals\Classes\CmsPermissions;
use NumenCode\Widgets\Controllers\Promotions as PromotionsController;

class Plugin extends PluginBase
{
    public $require = [
        'NumenCode.Fundamentals',
        'RainLab.Translate',
    ];

    public function boot()
    {
        CmsPermissions::revokeDelete(['owners', 'publishers'], PromotionsController::class);
    }

    public function registerComponents()
    {
        return [
            Counters::class   => 'counters',
            Promotions::class => 'promotions',
            Features::class   => 'features',
            Highlights::class => 'highlights',
        ];
    }

    public function registerPageSnippets()
    {
        return [
            Counters::class   => 'counters',
            Promotions::class => 'promotions',
            Features::class   => 'features',
            Highlights::class => 'highlights',
        ];
    }

    public function registerPermissions()
    {
        return [
            'numencode.widgets.manage_counters'   => 'numencode.widgets::lang.permissions.counters',
            'numencode.widgets.manage_promotions' => 'numencode.widgets::lang.permissions.promotions',
            'numencode.widgets.manage_features'   => 'numencode.widgets::lang.permissions.features',
            'numencode.widgets.manage_highlights' => 'numencode.widgets::lang.permissions.highlights',
        ];
    }
}
