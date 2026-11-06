<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Tests;

use Backend\Facades\BackendAuth;
use Backend\Models\User;
use Backend\Models\UserGroup;
use Cms\Classes\Theme;
use NumenCode\Fundamentals\Classes\CmsPermissions;
use NumenCode\Widgets\Components;
use NumenCode\Widgets\Controllers;
use NumenCode\Widgets\Models;
use NumenCode\Widgets\Models\WidgetGroup;
use System\Tests\Bootstrap\PluginTestCase;

/**
 * Base test case: the widget types, a helper to create groups and a backend user helper.
 */
abstract class WidgetsTestCase extends PluginTestCase
{
    /**
     * Widget types: code => [group model, item model, controller, component, item data].
     */
    public const TYPES = [
        'promotions' => [Models\PromotionGroup::class, Models\PromotionItem::class, Controllers\Promotions::class, Components\Promotions::class, ['content' => '<p>Text</p>', 'link' => '/a']],
        'features'   => [Models\FeatureGroup::class, Models\FeatureItem::class, Controllers\Features::class, Components\Features::class, ['content' => '<p>Text</p>', 'link' => '/a']],
        'highlights' => [Models\HighlightGroup::class, Models\HighlightItem::class, Controllers\Highlights::class, Components\Highlights::class, ['content' => '<p>Text</p>', 'link' => '/a']],
        'gallery'    => [Models\GalleryGroup::class, Models\GalleryItem::class, Controllers\Gallery::class, Components\Gallery::class, ['picture' => '/photo.jpg']],
        'counters'   => [Models\CounterGroup::class, Models\CounterItem::class, Controllers\Counters::class, Components\Counters::class, ['value' => '42', 'suffix' => '+']],
    ];

    public function setUp(): void
    {
        parent::setUp();

        // The widget models soft-implement Translate's behavior; when the plugin is installed, its tables are needed
        if (class_exists(\Winter\Translate\Plugin::class)) {
            $this->instantiatePlugin('Winter.Translate');
        }
    }

    public function tearDown(): void
    {
        CmsPermissions::flush();
        BackendAuth::logout();

        parent::tearDown();
    }

    /**
     * Creates a group with its items (through the relation repeater field) and returns it freshly loaded.
     *
     * @param class-string<WidgetGroup> $class
     * @param array<int, array<string, mixed>> $items
     * @param array<string, mixed> $attributes
     */
    protected function makeGroup(string $class, array $items = [], array $attributes = []): WidgetGroup
    {
        $group = new $class($attributes + ['title' => 'Group']);
        $group->items_list = $items;
        $group->save();

        return $class::withUnpublished()->with(['items' => fn ($query) => $query->withUnpublished()])->find($group->id);
    }

    /**
     * Logs in a backend user who is a member of the given user group.
     */
    protected function loginBackendUser(string $groupCode = 'editors'): User
    {
        $group = UserGroup::where('code', $groupCode)->first()
            ?? UserGroup::create(['name' => ucfirst($groupCode), 'code' => $groupCode]);

        $user = User::create([
            'first_name'            => 'Test',
            'last_name'             => 'User',
            'login'                 => 'tester',
            'email'                 => 'tester@example.com',
            'password'              => 'Widgets-Test-Password-1',
            'password_confirmation' => 'Widgets-Test-Password-1',
            'is_activated'          => true,
        ]);
        $user->groups()->attach($group);

        BackendAuth::setUser($user->fresh());

        return $user;
    }

    /**
     * Uses the fixture theme (tests/fixtures/themes/widgets) as the active theme.
     */
    protected function useFixtureTheme(): void
    {
        $this->app->setThemesPath(plugins_path('numencode/widgets/tests/fixtures/themes'));
        config(['cms.activeTheme' => 'widgets']);
        Theme::resetCache();
    }
}
