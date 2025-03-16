<?php //namespace NumenCode\Widgets\Tests\Components;
//
//use PluginTestCase;
//use NumenCode\Widgets\Components\Counters;
//
//class CountersTest extends PluginTestCase
//{
//    /**
//     * Test initialization and property setup of Counters component.
//     */
//    public function testComponentInitialization(): void
//    {
//        // Create an instance of the Counters component
//        $component = new Counters();
//
//        // Verify component details
//        $details = $component->componentDetails();
//        $this->assertArrayHasKey('name', $details);
//        $this->assertArrayHasKey('description', $details);
//
//        // Verify defineProperties method returns expected properties
//        $properties = $component->defineProperties();
//        $this->assertArrayHasKey('first_title', $properties);
//        $this->assertArrayHasKey('first_value', $properties);
//        $this->assertArrayHasKey('second_title', $properties);
//        $this->assertArrayHasKey('third_icon', $properties);
//        $this->assertArrayHasKey('fourth_value', $properties);
//
//        // Check default values of properties
//        $this->assertEquals('100', $properties['first_value']['default']);
//        $this->assertEquals('^[0-9]+$', $properties['first_value']['validationPattern']);
//        $this->assertEquals('numencode.widgets::lang.validation.numeric', $properties['first_value']['validationMessage']);
//    }
//
//    /**
//     * Test onRun method populates counters property correctly.
//     */
//    public function testOnRunPopulatesCounters(): void
//    {
//        // Mock component with custom properties
//        $component = new Counters();
//        $component->setProperty('first_title', 'First Counter');
//        $component->setProperty('first_icon', 'icon-first');
//        $component->setProperty('first_value', '123');
//
//        $component->setProperty('second_title', 'Second Counter');
//        $component->setProperty('second_icon', 'icon-second');
//        $component->setProperty('second_value', '456');
//
//        $component->setProperty('third_title', 'Third Counter');
//        $component->setProperty('third_icon', 'icon-third');
//        $component->setProperty('third_value', '789');
//
//        $component->setProperty('fourth_title', 'Fourth Counter');
//        $component->setProperty('fourth_icon', 'icon-fourth');
//        $component->setProperty('fourth_value', '101');
//
//        // Run the component
//        $component->onRun();
//
//        // Verify the counters' property is populated correctly
//        $this->assertIsArray($component->counters);
//        $this->assertCount(4, $component->counters);
//
//        $this->assertEquals([
//            'title' => 'First Counter',
//            'icon'  => 'icon-first',
//            'value' => '123',
//        ], $component->counters['first']);
//
//        $this->assertEquals([
//            'title' => 'Second Counter',
//            'icon'  => 'icon-second',
//            'value' => '456',
//        ], $component->counters['second']);
//
//        $this->assertEquals([
//            'title' => 'Third Counter',
//            'icon'  => 'icon-third',
//            'value' => '789',
//        ], $component->counters['third']);
//
//        $this->assertEquals([
//            'title' => 'Fourth Counter',
//            'icon'  => 'icon-fourth',
//            'value' => '101',
//        ], $component->counters['fourth']);
//    }
//}
