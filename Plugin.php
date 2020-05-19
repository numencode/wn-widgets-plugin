<?php namespace NumenCode\Widgets;

use System\Classes\PluginBase;
use NumenCode\Widgets\Controllers\Promotions;
use NumenCode\Fundamentals\Classes\CmsPermissions;

class Plugin extends PluginBase
{
    public $require = ['NumenCode.Fundamentals'];

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
        CmsPermissions::revokeDelete(['owners', 'publishers'], Promotions::class);
    }

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }
}
