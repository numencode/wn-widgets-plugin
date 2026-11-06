<?php

declare(strict_types=1);

namespace NumenCode\Widgets;

use Backend;
use BackendAuth;
use NumenCode\Widgets\Components\Counters;
use NumenCode\Widgets\Components\Features;
use NumenCode\Widgets\Components\Gallery;
use NumenCode\Widgets\Components\Highlights;
use NumenCode\Widgets\Components\Promotions;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = ['NumenCode.Fundamentals'];

    /**
     * Widget types with a backend page: code => side menu icon. The code is used in the URL, the
     * permission (numencode.widgets.manage_{code}) and the lang section.
     */
    public const WIDGET_TYPES = [
        'promotions' => 'icon-picture-o',
        'features'   => 'icon-eye',
        'highlights' => 'icon-star',
        'gallery'    => 'icon-camera-retro',
        'counters'   => 'icon-tachometer',
    ];

    public function pluginDetails(): array
    {
        return [
            'name'        => 'numencode.widgets::lang.plugin.name',
            'description' => 'numencode.widgets::lang.plugin.description',
            'author'      => 'Blaz Orazem',
            'icon'        => 'icon-th-large',
            'homepage'    => 'https://www.numencode.com',
        ];
    }

    public function registerComponents(): array
    {
        return [
            Promotions::class => 'promotions',
            Features::class   => 'features',
            Highlights::class => 'highlights',
            Gallery::class    => 'gallery',
            Counters::class   => 'counters',
        ];
    }

    public function registerPageSnippets(): array
    {
        return $this->registerComponents();
    }

    public function registerNavigation(): array
    {
        $sideMenu = [];

        foreach (self::WIDGET_TYPES as $code => $icon) {
            $sideMenu[$code] = [
                'label'       => 'numencode.widgets::lang.' . $code . '.name',
                'icon'        => $icon,
                'url'         => Backend::url('numencode/widgets/' . $code),
                'permissions' => ['numencode.widgets.manage_' . $code],
            ];
        }

        return [
            'widgets' => [
                'label'       => 'numencode.widgets::lang.plugin.name',
                'url'         => $this->getWidgetsUrl(),
                'icon'        => 'icon-th-large',
                'permissions' => ['numencode.widgets.manage_*'],
                'order'       => 399,
                'sideMenu'    => $sideMenu,
            ],
        ];
    }

    public function registerPermissions(): array
    {
        $permissions = [];

        foreach (array_keys(self::WIDGET_TYPES) as $code) {
            $permissions['numencode.widgets.manage_' . $code] = [
                'tab'   => 'numencode.widgets::lang.plugin.name',
                'label' => 'numencode.widgets::lang.permissions.' . $code,
            ];
        }

        return $permissions;
    }

    /**
     * URL of the main menu item: the first widget type the backend user may manage.
     */
    protected function getWidgetsUrl(): string
    {
        $user = BackendAuth::getUser();

        foreach (array_keys(self::WIDGET_TYPES) as $code) {
            if (!$user || $user->hasAccess('numencode.widgets.manage_' . $code)) {
                return Backend::url('numencode/widgets/' . $code);
            }
        }

        return Backend::url('backend');
    }
}
