<?php namespace NumenCode\Widgets;

use System\Classes\PluginBase;
use NumenCode\Widgets\Components\Promotions;
use NumenCode\Fundamentals\Classes\CmsPermissions;
use NumenCode\Widgets\Controllers\Promotions as PromotionsController;

class Plugin extends PluginBase
{
    public $require = [
        'NumenCode.Fundamentals',
        'RainLab.Translate',
    ];

    public function pluginDetails()
    {
        return [
            'name'        => 'numencode.widgets::lang.plugin.name',
            'description' => 'numencode.widgets::lang.plugin.description',
            'author'      => 'Blaz Orazem',
            'icon'        => 'oc-icon-briefcase',
            'homepage'    => 'https://github.com/numencode/widgets-plugin',
        ];
    }

    public function boot()
    {
        CmsPermissions::revokeDelete(['owners', 'publishers'], PromotionsController::class);
    }

    public function registerComponents()
    {
        return [
            Promotions::class => 'promotions',
        ];
    }

    public function registerPermissions()
    {
        return [
            'numencode.widgets.manage_promotions' => 'numencode.widgets::lang.permissions.promotions',
        ];
    }

    public function registerSettings()
    {
        //
    }
}
