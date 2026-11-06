<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Tests\Models;

use Db;
use NumenCode\Widgets\Models\CounterGroup;
use NumenCode\Widgets\Models\FeatureGroup;
use NumenCode\Widgets\Models\GalleryItem;
use NumenCode\Widgets\Models\PromotionGroup;
use NumenCode\Widgets\Models\WidgetGroup;
use NumenCode\Widgets\Models\WidgetItem;
use NumenCode\Widgets\Tests\WidgetsTestCase;
use Schema;
use Winter\Storm\Exception\ValidationException;

class WidgetModelsTest extends WidgetsTestCase
{
    public function testEveryTypeSavesItsItemsInOrder(): void
    {
        foreach (self::TYPES as $code => [$groupClass, $itemClass, , , $data]) {
            $group = $this->makeGroup($groupClass, [
                ['title' => 'First'] + $data,
                ['title' => 'Second', 'is_published' => false] + $data,
            ]);

            $this->assertInstanceOf(WidgetGroup::class, $group, $code);
            $this->assertSame(['First', 'Second'], $group->items->pluck('title')->all(), $code);
            $this->assertSame([1, 2], $group->items->pluck('sort_order')->map(fn ($order) => (int) $order)->all(), $code);
            $this->assertSame([true, false], $group->items->pluck('is_published')->all(), $code);

            $item = $group->items->first();
            $this->assertInstanceOf($itemClass, $item, $code);
            $this->assertInstanceOf(WidgetItem::class, $item, $code);
            $this->assertSame($group->id, $item->group->id, $code);
            $this->assertTrue($group->is_published, $code);
        }
    }

    public function testExistingItemsAreUpdatedNotRecreated(): void
    {
        $group = $this->makeGroup(FeatureGroup::class, [['title' => 'A'], ['title' => 'B']]);
        [$first, $second] = $group->items->pluck('id')->all();

        $group->items_list = [['id' => $second, 'title' => 'B2'], ['title' => 'C']];
        $group->save();

        $items = FeatureGroup::find($group->id)->items;

        $this->assertSame(['B2', 'C'], $items->pluck('title')->all());
        $this->assertSame($second, $items->first()->id);
        $this->assertNotContains($first, $items->pluck('id')->all(), 'Removed items are deleted');
    }

    public function testNewRecordsArePublishedByDefault(): void
    {
        foreach (self::TYPES as $code => [$groupClass, $itemClass]) {
            $this->assertTrue((new $groupClass())->is_published, $code);
            $this->assertTrue((new $itemClass())->is_published, $code);

            // Also in the database, for records inserted without the model
            $table = (new $groupClass())->getTable();
            $id = Db::table($table)->insertGetId(['title' => 'Raw']);
            $this->assertEquals(1, Db::table($table)->find($id)->is_published, $code);
        }
    }

    public function testDeletingAGroupDeletesItsItems(): void
    {
        foreach (self::TYPES as $code => [$groupClass, $itemClass, , , $data]) {
            $group = $this->makeGroup($groupClass, [['title' => 'Item'] + $data]);

            $group->delete();

            $this->assertNull($groupClass::withUnpublished()->find($group->id), $code);
            $this->assertSame(0, $itemClass::withUnpublished()->count(), $code);
            $this->assertFalse(Schema::hasColumn($group->getTable(), 'deleted_at'), $code . ': no soft deletes');
        }
    }

    public function testGroupOptionsListAllGroupsByTitle(): void
    {
        $b = $this->makeGroup(PromotionGroup::class, [], ['title' => 'Bravo']);
        $a = $this->makeGroup(PromotionGroup::class, [], ['title' => 'Alpha', 'is_published' => false]);

        $this->assertSame([$a->id => 'Alpha', $b->id => 'Bravo'], PromotionGroup::getGroupOptions());
    }

    public function testGroupTitleIsRequired(): void
    {
        $this->expectException(ValidationException::class);

        $this->makeGroup(PromotionGroup::class, [], ['title' => '']);
    }

    public function testCounterValueIsRequiredAndNumeric(): void
    {
        foreach (['', 'many'] as $value) {
            try {
                $this->makeGroup(CounterGroup::class, [['title' => 'Customers', 'value' => $value]]);
                $this->fail('A counter value "' . $value . '" must not be accepted');
            } catch (ValidationException $e) {
                $this->assertStringContainsString('Item 1:', $e->getMessage());
            }
        }

        $group = $this->makeGroup(CounterGroup::class, [['title' => 'Satisfaction', 'value' => '98.5', 'suffix' => ' %']]);

        $this->assertSame('98.5', $group->items->first()->value);
        $this->assertSame(1, CounterGroup::count(), 'The groups with invalid counters were not saved');
    }

    public function testGalleryItemsOnlyHaveTheirOwnFields(): void
    {
        $item = new GalleryItem(['title' => 'Photo', 'content' => 'ignored', 'link' => 'ignored', 'picture' => '/a.jpg']);

        $this->assertSame('Photo', $item->title);
        $this->assertSame('/a.jpg', $item->picture);
        $this->assertNull($item->content);
        $this->assertNull($item->link);
        $this->assertSame(['title', 'subtitle'], $item->translatable);
    }
}
