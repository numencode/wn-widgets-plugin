<?php namespace NumenCode\Widgets\Tests\Components;

use Mockery;
use PluginTestCase;
use NumenCode\Widgets\Components\Gallery;
use NumenCode\Widgets\Models\GalleryGroup;

class GalleryTest extends PluginTestCase
{
    /**
     * Test component initialization and properties.
     */
    public function testComponentInitialization(): void
    {
        $component = new Gallery();

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
     * Test the getTitleOptions method fetches titles from GalleryGroup model.
     */
    public function testGetTitleOptions(): void
    {
        // Mock GalleryGroup model to return sample data
        $mock = Mockery::mock('alias:' . GalleryGroup::class);
        $mock->shouldReceive('lists')->once()->andReturn([
            1 => 'Gallery 1',
            2 => 'Gallery 2',
        ]);

        $component = new Gallery();

        $titleOptions = $component->getTitleOptions();
        $this->assertArrayHasKey(1, $titleOptions);
        $this->assertArrayHasKey(2, $titleOptions);
        $this->assertEquals('Gallery 1', $titleOptions[1]);
        $this->assertEquals('Gallery 2', $titleOptions[2]);
    }

    /**
     * Test the getLayoutOptions method returns the expected layouts.
     */
    public function testGetLayoutOptions(): void
    {
        $component = new Gallery();

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
        // Mock GalleryGroup::find to return a dummy group
        $mock = Mockery::mock('alias:' . GalleryGroup::class);
        $mock->shouldReceive('find')->with(1)->once()->andReturn([
            'id'    => 1,
            'title' => 'Sample Group',
        ]);

        $component = new Gallery();
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
        $component = Mockery::mock(Gallery::class)->makePartial();

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
        $component = Mockery::mock(Gallery::class)->makePartial();

        // Mock renderPartial to check the rendered layout
        $component->shouldReceive('renderPartial')
            ->with('@default.htm')
            ->andReturn('Rendered Default Layout');

        $component->setProperty('layout', null);

        $output = $component->onRender();
        $this->assertEquals('Rendered Default Layout', $output);
    }
}
