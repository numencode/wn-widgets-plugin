<?php namespace NumenCode\Widgets\Tests\Components;

use Mockery;
use PluginTestCase;
use NumenCode\Widgets\Components\Highlights;
use NumenCode\Widgets\Models\HighlightGroup;

class HighlightsTest extends PluginTestCase
{
    /**
     * Test component initialization and properties.
     */
    public function testComponentInitialization(): void
    {
        $component = new Highlights();

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
     * Test the getTitleOptions method fetches titles from HighlightGroup model.
     */
    public function testGetTitleOptions(): void
    {
        // Mock HighlightGroup model to return sample data
        $mock = Mockery::mock('alias:' . HighlightGroup::class);
        $mock->shouldReceive('lists')->once()->andReturn([
            1 => 'Highlight Group 1',
            2 => 'Highlight Group 2',
        ]);

        $component = new Highlights();

        $titleOptions = $component->getTitleOptions();
        $this->assertArrayHasKey(1, $titleOptions);
        $this->assertArrayHasKey(2, $titleOptions);
        $this->assertEquals('Highlight Group 1', $titleOptions[1]);
        $this->assertEquals('Highlight Group 2', $titleOptions[2]);
    }

    /**
     * Test the getLayoutOptions method returns the expected layouts.
     */
    public function testGetLayoutOptions(): void
    {
        $component = new Highlights();

        $layoutOptions = $component->getLayoutOptions();
        $this->assertArrayHasKey('default', $layoutOptions);
        $this->assertEquals('Default', $layoutOptions['default']);
    }

    /**
     * Test the onRun method loads the correct group.
     */
    public function testOnRunLoadsGroup(): void
    {
        // Mock HighlightGroup::find to return a dummy group
        $mock = Mockery::mock('alias:' . HighlightGroup::class);
        $mock->shouldReceive('find')->with(1)->once()->andReturn([
            'id'    => 1,
            'title' => 'Sample Highlight Group',
        ]);

        $component = new Highlights();
        $component->setProperty('title', 1);

        $component->onRun();

        $this->assertIsArray($component->group);
        $this->assertEquals(1, $component->group['id']);
        $this->assertEquals('Sample Highlight Group', $component->group['title']);
    }

    /**
     * Test the onRender method selects the correct layout for rendering.
     */
    public function testOnRenderSelectsCorrectLayout(): void
    {
        $component = Mockery::mock(Highlights::class)->makePartial();

        // Mock renderPartial to check the rendered layout
        $component->shouldReceive('renderPartial')
            ->with('@default.htm')
            ->andReturn('Rendered Default Layout');

        $component->setProperty('layout', 'default');

        $output = $component->onRender();
        $this->assertEquals('Rendered Default Layout', $output);
    }

    /**
     * Test the onRender method defaults to the default layout when not set.
     */
    public function testOnRenderDefaultsToDefaultLayout(): void
    {
        $component = Mockery::mock(Highlights::class)->makePartial();

        // Mock renderPartial to check the rendered layout
        $component->shouldReceive('renderPartial')
            ->with('@default.htm')
            ->andReturn('Rendered Default Layout');

        $component->setProperty('layout', null);

        $output = $component->onRender();
        $this->assertEquals('Rendered Default Layout', $output);
    }
}
