<?php namespace NumenCode\Widgets\Components;

use Cms\Classes\ComponentBase;

class Counters extends ComponentBase
{
    public $counters;

    public function componentDetails(): array
    {
        return [
            'name'        => 'numencode.widgets::lang.counters.name',
            'description' => 'numencode.widgets::lang.counters.description',
        ];
    }

    public function defineProperties(): array
    {
        return [
            'first_title'  => [
                'title' => 'numencode.widgets::lang.counters.title',
                'type'  => 'string',
                'group' => 'numencode.widgets::lang.counters.first_counter',
            ],
            'first_icon'   => [
                'title'       => 'numencode.widgets::lang.counters.icon',
                'description' => 'numencode.widgets::lang.counters.icon_hint',
                'type'        => 'string',
                'group'       => 'numencode.widgets::lang.counters.first_counter',
            ],
            'first_value'  => [
                'title'             => 'numencode.widgets::lang.counters.value',
                'type'              => 'string',
                'default'           => '100',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Value can contain only numeric symbols',
                'group'             => 'numencode.widgets::lang.counters.first_counter',
            ],
            'second_title' => [
                'title' => 'numencode.widgets::lang.counters.title',
                'type'  => 'string',
                'group' => 'numencode.widgets::lang.counters.second_counter',
            ],
            'second_icon'  => [
                'title'       => 'numencode.widgets::lang.counters.icon',
                'description' => 'numencode.widgets::lang.counters.icon_hint',
                'type'        => 'string',
                'group'       => 'numencode.widgets::lang.counters.second_counter',
            ],
            'second_value' => [
                'title'             => 'numencode.widgets::lang.counters.value',
                'type'              => 'string',
                'default'           => '100',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Value can contain only numeric symbols',
                'group'             => 'numencode.widgets::lang.counters.second_counter',
            ],
            'third_title'  => [
                'title' => 'numencode.widgets::lang.counters.title',
                'type'  => 'string',
                'group' => 'numencode.widgets::lang.counters.third_counter',
            ],
            'third_icon'   => [
                'title'       => 'numencode.widgets::lang.counters.icon',
                'description' => 'numencode.widgets::lang.counters.icon_hint',
                'type'        => 'string',
                'group'       => 'numencode.widgets::lang.counters.third_counter',
            ],
            'third_value'  => [
                'title'             => 'numencode.widgets::lang.counters.value',
                'type'              => 'string',
                'default'           => '100',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Value can contain only numeric symbols',
                'group'             => 'numencode.widgets::lang.counters.third_counter',
            ],
            'fourth_title' => [
                'title' => 'numencode.widgets::lang.counters.title',
                'type'  => 'string',
                'group' => 'numencode.widgets::lang.counters.fourth_counter',
            ],
            'fourth_icon'  => [
                'title'       => 'numencode.widgets::lang.counters.icon',
                'description' => 'numencode.widgets::lang.counters.icon_hint',
                'type'        => 'string',
                'group'       => 'numencode.widgets::lang.counters.fourth_counter',
            ],
            'fourth_value' => [
                'title'             => 'numencode.widgets::lang.counters.value',
                'type'              => 'string',
                'default'           => '100',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Value can contain only numeric symbols',
                'group'             => 'numencode.widgets::lang.counters.fourth_counter',
            ],
        ];
    }

    public function onRun()
    {
        $this->counters = [
            'first'  => [
                'title' => $this->property('first_title'),
                'icon'  => $this->property('first_icon'),
                'value' => $this->property('first_value'),
            ],
            'second' => [
                'title' => $this->property('second_title'),
                'icon'  => $this->property('second_icon'),
                'value' => $this->property('second_value'),
            ],
            'third'  => [
                'title' => $this->property('third_title'),
                'icon'  => $this->property('third_icon'),
                'value' => $this->property('third_value'),
            ],
            'fourth' => [
                'title' => $this->property('fourth_title'),
                'icon'  => $this->property('fourth_icon'),
                'value' => $this->property('fourth_value'),
            ],
        ];
    }
}
