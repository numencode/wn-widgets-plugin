<?php namespace NumenCode\Widgets;

use Backend;
use BackendAuth;
use System\Classes\PluginBase;
use NumenCode\Widgets\Components\Gallery;
use NumenCode\Widgets\Components\Counters;
use NumenCode\Widgets\Components\Features;
use NumenCode\Widgets\Components\Highlights;
use NumenCode\Widgets\Components\Promotions;
use NumenCode\Fundamentals\Classes\CmsPermissions;
use NumenCode\Widgets\Controllers\Promotions as PromotionsController;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name'        => 'numencode.widgets::lang.plugin.name',
            'description' => 'numencode.widgets::lang.plugin.description',
            'author'      => 'Blaz Orazem',
            'icon'        => 'oc-icon-briefcase',
            'homepage'    => 'https://github.com/numencode/wn-widgets-plugin',
        ];
    }

    public function boot()
    {
        CmsPermissions::revokeDelete(['owners', 'publishers'], PromotionsController::class);
    }

    public function registerComponents(): array
    {
        return [
            Counters::class   => 'counters',
            Promotions::class => 'promotions',
            Features::class   => 'features',
            Highlights::class => 'highlights',
            Gallery::class    => 'gallery',
        ];
    }

    public function registerPageSnippets(): array
    {
        return [
            Counters::class   => 'counters',
            Promotions::class => 'promotions',
            Features::class   => 'features',
            Highlights::class => 'highlights',
            Gallery::class    => 'gallery',
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'widgets' => [
                'label'       => 'numencode.widgets::lang.plugin.name',
                'url'         => $this->getWidgetsUrl(),
                'icon'        => 'icon-briefcase',
                'permissions' => ['numencode.widgets.manage_*'],
                'order'       => 399,
                'sideMenu'    => [
                    'promotions' => [
                        'label'       => 'numencode.widgets::lang.promotions.name',
                        'icon'        => 'icon-picture-o',
                        'url'         => Backend::url('numencode/widgets/promotions'),
                        'permissions' => ['numencode.widgets.manage_promotions'],
                    ],
                    'features'   => [
                        'label'       => 'numencode.widgets::lang.features.name',
                        'icon'        => 'icon-eye',
                        'url'         => Backend::url('numencode/widgets/features'),
                        'permissions' => ['numencode.widgets.manage_features'],
                    ],
                    'highlights' => [
                        'label'       => 'numencode.widgets::lang.highlights.name',
                        'icon'        => 'icon-star',
                        'url'         => Backend::url('numencode/widgets/highlights'),
                        'permissions' => ['numencode.widgets.manage_highlights'],
                    ],
                    'gallery'    => [
                        'label'       => 'numencode.widgets::lang.gallery.name',
                        'icon'        => 'icon-camera-retro',
                        'url'         => Backend::url('numencode/widgets/gallery'),
                        'permissions' => ['numencode.widgets.manage_gallery'],
                    ],
                ],
            ],
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'numencode.widgets.manage_promotions' => [
                'tab'   => 'numencode.widgets::lang.plugin.name',
                'label' => 'numencode.widgets::lang.permissions.promotions',
            ],
            'numencode.widgets.manage_features'   => [
                'tab'   => 'numencode.widgets::lang.plugin.name',
                'label' => 'numencode.widgets::lang.permissions.features',
            ],
            'numencode.widgets.manage_highlights' => [
                'tab'   => 'numencode.widgets::lang.plugin.name',
                'label' => 'numencode.widgets::lang.permissions.highlights',
            ],
            'numencode.widgets.manage_gallery' => [
                'tab'   => 'numencode.widgets::lang.plugin.name',
                'label' => 'numencode.widgets::lang.permissions.gallery',
            ],
        ];
    }

    protected function getWidgetsUrl()
    {
        $user = BackendAuth::getUser();

        if ($user->hasPermission('numencode.widgets.manage_promotions')) {
            return Backend::url('numencode/widgets/promotions');
        }

        if ($user->hasPermission('numencode.widgets.manage_features')) {
            return Backend::url('numencode/widgets/features');
        }

        if ($user->hasPermission('numencode.widgets.manage_highlights')) {
            return Backend::url('numencode/widgets/highlights');
        }

        if ($user->hasPermission('numencode.widgets.manage_gallery')) {
            return Backend::url('numencode/widgets/gallery');
        }

        return Backend::url('/');
    }
}
