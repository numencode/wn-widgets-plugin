<?php namespace NumenCode\Widgets;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
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

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }
}
