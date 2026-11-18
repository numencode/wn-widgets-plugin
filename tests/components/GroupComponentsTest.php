<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Tests\Components;

use Cms\Classes\Controller;
use Cms\Classes\Theme;
use NumenCode\Widgets\Classes\GroupComponent;
use NumenCode\Widgets\Components\Counters;
use NumenCode\Widgets\Components\Features;
use NumenCode\Widgets\Components\Gallery;
use NumenCode\Widgets\Models\FeatureGroup;
use NumenCode\Widgets\Models\GalleryGroup;
use NumenCode\Widgets\Models\PromotionGroup;
use NumenCode\Widgets\Tests\WidgetsTestCase;

class GroupComponentsTest extends WidgetsTestCase
{
    public function tearDown(): void
    {
        foreach (self::TYPES as [, , , $component]) {
            $component::$overrideLayout = true;
        }

        parent::tearDown();
    }

    /**
     * @param class-string<GroupComponent> $class
     * @param array<string, mixed> $properties
     */
    protected function runComponent(string $class, array $properties): GroupComponent
    {
        $component = new $class(null, $properties);
        $component->onRun();

        return $component;
    }

    public function testComponentsHaveGroupAndLayoutProperties(): void
    {
        foreach (self::TYPES as $code => [, , , $class]) {
            $component = new $class();
            $properties = $component->defineProperties();
            $details = $component->componentDetails();

            $this->assertSame('', $properties['group']['default'], $code);
            $this->assertSame('dropdown', $properties['group']['type'], $code);
            $this->assertArrayHasKey('required', $properties['group']['validation'], $code);
            $this->assertSame('@default', $properties['layout']['default'], $code);
            $this->assertSame('numencode.widgets::lang.' . $code . '.name', $details['name'], $code);
            $this->assertNotSame($details['name'], trans($details['name']), $code . ': translated');
            $this->assertSame(\NumenCode\Widgets\Plugin::WIDGET_TYPES[$code], $details['icon'], $code . ': menu icon');
        }

        $this->assertArrayNotHasKey('items_per_row', (new Features())->defineProperties());
    }

    /**
     * Winter's Inspector doesn't translate "placeholder" or validation messages, so they must be passed translated.
     */
    public function testBricksEditUrlNeedsThePermissionToManageTheWidgetType(): void
    {
        $this->assertNull(Features::bricksEditUrl(), 'no backend user');

        $user = $this->loginBackendUser();
        $this->assertNull(Features::bricksEditUrl(), 'without the permission');

        // Core doesn't let the logged-in user change their own permissions
        \Backend\Facades\BackendAuth::logout();
        $user->permissions = ['numencode.widgets.manage_features' => 1];
        $user->save();
        \Backend\Facades\BackendAuth::setUser($user->fresh());

        $this->assertSame('numencode/widgets/features/update/{group}', Features::bricksEditUrl());
        $this->assertNull(Gallery::bricksEditUrl(), 'per widget type');
    }

    public function testGroupPlaceholderAndValidationMessageAreTranslated(): void
    {
        $group = (new Features())->defineProperties()['group'];

        $this->assertSame(trans('numencode.widgets::lang.component.group_placeholder'), $group['placeholder']);
        $this->assertStringNotContainsString('::', $group['placeholder']);
        $this->assertStringNotContainsString('::', $group['validation']['required']['message']);
    }

    public function testGalleryItemsPerRowIsADropdownOfGridDivisors(): void
    {
        $property = (new Gallery())->defineProperties()['items_per_row'];

        $this->assertSame('dropdown', $property['type']);
        $this->assertSame([1, 2, 3, 4, 6, 12], array_keys($property['options']));
        $this->assertSame('4', $property['default']);
    }

    public function testGroupOptionsComeFromTheGroupModel(): void
    {
        $group = $this->makeGroup(FeatureGroup::class, [], ['title' => 'Services']);
        $this->makeGroup(GalleryGroup::class, [], ['title' => 'Not a feature']);

        $this->assertSame([$group->id => 'Services'], (new Features())->getGroupOptions());
    }

    public function testLayoutSelectionIsEnabledPerComponent(): void
    {
        foreach (self::TYPES as $code => [, , , $class]) {
            $this->assertTrue($class::$overrideLayout, $code);
        }

        Gallery::$overrideLayout = false;

        $this->assertFalse(Gallery::$overrideLayout);
        $this->assertTrue(Features::$overrideLayout, 'Switching one component off does not affect the others');
        $this->assertTrue(Counters::$overrideLayout);
    }

    public function testLoadsThePublishedGroupWithItsPublishedItems(): void
    {
        $group = $this->makeGroup(PromotionGroup::class, [
            ['title' => 'Visible'],
            ['title' => 'Hidden', 'is_published' => false],
        ]);

        $component = $this->runComponent(\NumenCode\Widgets\Components\Promotions::class, ['group' => (string) $group->id]);

        $this->assertSame($group->id, $component->group->id);
        $this->assertSame(['Visible'], $component->group->items->pluck('title')->all());
    }

    public function testNoGroupIsLoadedWhenItIsMissingOrUnpublished(): void
    {
        $unpublished = $this->makeGroup(FeatureGroup::class, [['title' => 'A']], ['is_published' => false]);

        foreach (['', 'abc', '999', (string) $unpublished->id] as $id) {
            $this->assertNull($this->runComponent(Features::class, ['group' => $id])->group, 'group=' . $id);
        }
    }

    public function testBackendUsersSeeUnpublishedGroups(): void
    {
        $unpublished = $this->makeGroup(FeatureGroup::class, [['title' => 'A', 'is_published' => false]], ['is_published' => false]);
        $this->loginBackendUser();

        $component = $this->runComponent(Features::class, ['group' => (string) $unpublished->id]);

        $this->assertSame(['A'], $component->group->items->pluck('title')->all());
    }

    /**
     * @dataProvider itemsPerRowProvider
     */
    public function testGalleryItemsPerRowIsNormalized(mixed $value, int $expected, int $width): void
    {
        $component = $this->runComponent(Gallery::class, ['items_per_row' => $value]);

        $this->assertSame($expected, $component->itemsPerRow);
        $this->assertSame($width, $component->columnWidth());
    }

    public static function itemsPerRowProvider(): array
    {
        return [
            'default'      => [null, 4, 3],
            'three'        => ['3', 3, 4],
            'five'         => ['5', 4, 3],
            'zero'         => ['0', 1, 12],
            'negative'     => ['-2', 1, 12],
            'thirteen'     => ['13', 12, 1],
            'not a number' => ['many', 4, 3],
        ];
    }

    public function testDefaultLayoutsRenderThroughTheCms(): void
    {
        $item = ['title' => 'Item one', 'subtitle' => 'Sub', 'content' => '<p>Rich <b>text</b></p>', 'picture' => '/photo.jpg', 'link' => '/go', 'link_title' => 'Go'];

        foreach (self::TYPES as $code => [$groupClass, , , , $data]) {
            $this->makeGroup($groupClass, [
                $item + $data,
                ['title' => 'Item two', 'value' => '7'],
                ['title' => 'Hidden item', 'value' => '1', 'is_published' => false],
            ], ['title' => ucfirst($code) . ' group', 'link' => '/all', 'link_title' => 'All']);
        }

        $this->useFixtureTheme();
        $html = (new Controller(Theme::getActiveTheme()))->run('/widgets')->getContent();

        preg_match_all('/([A-Z0-9]+)\[(.*?)\]\n/s', $html . "\n", $matches, PREG_SET_ORDER);
        $out = [];
        foreach ($matches as [, $name, $content]) {
            $out[$name] = $content;
        }

        $this->assertStringNotContainsString('Hidden item', $html, 'Unpublished items are not rendered');

        // Promotions: one carousel per component, with a unique ID
        $this->assertStringContainsString('id="promotions-promotions"', $out['PROMOTIONS']);
        $this->assertStringContainsString('id="promotions-promotionsSecond"', $out['PROMOTIONS2']);
        $this->assertSame(2, substr_count($out['PROMOTIONS'], 'class="carousel-item'));
        $this->assertStringContainsString('<h3>Item one</h3>', $out['PROMOTIONS']);

        // Features: group title, rich content, link button only with a link
        $this->assertStringContainsString('<h2 class="mb-4">Features group</h2>', $out['FEATURES']);
        $this->assertStringContainsString('<p>Rich <b>text</b></p>', $out['FEATURES']);
        $this->assertSame(1, substr_count($out['FEATURES'], 'href="/go"'));
        $this->assertStringContainsString('href="/all"', $out['FEATURES']);
        $this->assertStringContainsString('card-img-top', $out['FEATURESMEDIA']);
        $this->assertStringContainsString('photo.jpg" alt="Item one" loading="lazy"', $out['FEATURESMEDIA']);

        // No group: nothing is rendered
        $this->assertSame('', trim($out['NOGROUP']));

        // Highlights and gallery
        $this->assertStringContainsString('flex-lg-row-reverse', $out['HIGHLIGHTS']);
        $this->assertStringContainsString('col-12 col-sm-6 col-lg-4', $out['GALLERY']);
        $this->assertStringContainsString('data-gallery="gallery"', $out['GALLERY']);
        $this->assertSame(1, substr_count($out['GALLERY'], '<figure'), 'Gallery items without a picture are skipped');

        // A theme layout in partials/gallery/ is used for the "galleryCustom" alias too
        $this->assertSame('CUSTOM Gallery group: Item one;Item two;', trim($out['CUSTOM']));

        // Counters: the value is printed as text, with the suffix
        $this->assertMatchesRegularExpression('/data-to="42">42<\/span>\+/', $out['COUNTERS']);
        $this->assertStringContainsString('data-to="7">7</span>', $out['COUNTERS']);
    }
}
