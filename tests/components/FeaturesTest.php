<?php namespace NumenCode\Widgets\Tests\Components;

use Mockery;
use PluginTestCase;
use NumenCode\Widgets\Components\Features;
use NumenCode\Widgets\Models\FeatureGroup;

class FeaturesTest extends PluginTestCase
{
    /**
     * Test component initialization and properties.
     */
    public function testComponentInitialization(): void
    {
        $component = new Features();

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
     * Test the getTitleOptions method fetches titles from FeatureGroup model.
     */
    public function testGetTitleOptions(): void
    {
        // Mock FeatureGroup model to return sample data
        $mock = Mockery::mock('alias:' . FeatureGroup::class);
        $mock->shouldReceive('lists')->once()->andReturn([
            1 => 'Feature Group 1',
            2 => 'Feature Group 2',
        ]);

        $component = new Features();

        $titleOptions = $component->getTitleOptions();
        $this->assertArrayHasKey(1, $titleOptions);
        $this->assertArrayHasKey(2, $titleOptions);
        $this->assertEquals('Feature Group 1', $titleOptions[1]);
        $this->assertEquals('Feature Group 2', $titleOptions[2]);
    }

    /**
     * Test the getLayoutOptions method returns the expected layouts.
     */
    public function testGetLayoutOptions(): void
    {
        $component = new Features();

        $layoutOptions = $component->getLayoutOptions();
        $this->assertArrayHasKey('default', $layoutOptions);
        $this->assertArrayHasKey('media', $layoutOptions);
        $this->assertEquals('Default', $layoutOptions['default']);
        $this->assertEquals('Default with pictures', $layoutOptions['media']);
    }

    /**
     * Test the onRun method loads the correct group.
     */
    public function testOnRunLoadsGroup(): void
    {
        // Mock FeatureGroup::find to return a dummy group
        $mock = Mockery::mock('alias:' . FeatureGroup::class);
        $mock->shouldReceive('find')->with(1)->once()->andReturn([
            'id'    => 1,
            'title' => 'Sample Group',
        ]);

        $component = new Features();
        $component->setProperty('title', 1);

        $component->onRun();

        $this->assertIsArray($component->group);
        $this->assertEquals(1, $component->group['id']);
        $this->assertEquals('Sample Group', $component->group['title']);
    }

    /**
     * Test the onRender method selects the correct layout for rendering.
     */
    public function testOnRenderSelectsCorrectLayout(): void
    {
        $component = Mockery::mock(Features::class)->makePartial();

        // Mock renderPartial to check the rendered layout
        $component->shouldReceive('renderPartial')
            ->with('@media.htm')
            ->andReturn('Rendered Media Layout');

        $component->setProperty('layout', 'media');

        $output = $component->onRender();
        $this->assertEquals('Rendered Media Layout', $output);
    }

    /**
     * Test the onRender method defaults to the default layout.
     */
    public function testOnRenderDefaultsToDefaultLayout(): void
    {
        $component = Mockery::mock(Features::class)->makePartial();

        // Mock renderPartial to check the rendered layout
        $component->shouldReceive('renderPartial')
            ->with('@default.htm')
            ->andReturn('Rendered Default Layout');

        $component->setProperty('layout', null);

        $output = $component->onRender();
        $this->assertEquals('Rendered Default Layout', $output);
    }
}
