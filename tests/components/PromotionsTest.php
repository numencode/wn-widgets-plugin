<?php namespace NumenCode\Widgets\Tests\Components;

use PluginTestCase;
use NumenCode\Widgets\Components\Promotions;
use NumenCode\Widgets\Models\PromotionGroup;
use Mockery;

class PromotionsTest extends PluginTestCase
{
    /**
     * Test component initialization and properties.
     */
    public function testComponentInitialization(): void
    {
        $component = new Promotions();

        // Verify component details
        $details = $component->componentDetails();
        $this->assertArrayHasKey('name', $details);
        $this->assertArrayHasKey('description', $details);

        // Verify defineProperties method returns expected properties
        $properties = $component->defineProperties();
        $this->assertArrayHasKey('title', $properties);
        $this->assertArrayHasKey('layout', $properties);

        // Check default values
        $this->assertEquals('default', $properties['title']['default']);
        $this->assertEquals('default', $properties['layout']['default']);
    }

    /**
     * Test the getTitleOptions method fetches titles from PromotionGroup model.
     */
    public function testGetTitleOptions(): void
    {
        // Mock PromotionGroup model to return sample data
        $mock = Mockery::mock('alias:' . PromotionGroup::class);
        $mock->shouldReceive('lists')->once()->andReturn([
            1 => 'Promotion Group 1',
            2 => 'Promotion Group 2',
        ]);

        $component = new Promotions();

        $titleOptions = $component->getTitleOptions();
        $this->assertArrayHasKey(1, $titleOptions);
        $this->assertArrayHasKey(2, $titleOptions);
        $this->assertEquals('Promotion Group 1', $titleOptions[1]);
        $this->assertEquals('Promotion Group 2', $titleOptions[2]);
    }

    /**
     * Test the getLayoutOptions method returns the expected layouts.
     */
//    public function testGetLayoutOptions(): void
//    {
//        $component = new Promotions();
//
//        $layoutOptions = $component->getLayoutOptions();
//        $this->assertArrayHasKey('default', $layoutOptions);
//        $this->assertEquals('Bootstrap 4', $layoutOptions['default']);
//    }

    /**
     * Test the onRun method loads the correct group.
     */
    public function testOnRunLoadsGroup(): void
    {
        // Mock PromotionGroup::find to return a dummy group
        $mock = Mockery::mock('alias:' . PromotionGroup::class);
        $mock->shouldReceive('find')->with(1)->once()->andReturn([
            'id'    => 1,
            'title' => 'Sample Promotion Group',
        ]);

        $component = new Promotions();
        $component->setProperty('title', 1);

        $component->onRun();

        $this->assertIsArray($component->group);
        $this->assertEquals(1, $component->group['id']);
        $this->assertEquals('Sample Promotion Group', $component->group['title']);
    }

    /**
     * Test the onRender method selects the correct layout for rendering.
     */
//    public function testOnRenderSelectsCorrectLayout(): void
//    {
//        $component = Mockery::mock(Promotions::class)->makePartial();
//
//        // Mock renderPartial to check the rendered layout
//        $component->shouldReceive('renderPartial')
//            ->with('@default.htm')
//            ->andReturn('Rendered Default Layout');
//
//        $component->setProperty('layout', 'default');
//
//        $output = $component->onRender();
//        $this->assertEquals('Rendered Default Layout', $output);
//    }

    /**
     * Test the onRender method defaults to the default layout when not set.
     */
//    public function testOnRenderDefaultsToDefaultLayout(): void
//    {
//        $component = Mockery::mock(Promotions::class)->makePartial();
//
//        // Mock renderPartial to check the rendered layout
//        $component->shouldReceive('renderPartial')
//            ->with('@default.htm')
//            ->andReturn('Rendered Default Layout');
//
//        $component->setProperty('layout', null);
//
//        $output = $component->onRender();
//        $this->assertEquals('Rendered Default Layout', $output);
//    }
}
