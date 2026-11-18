<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Classes;

use Backend\Facades\BackendAuth;
use Cms\Classes\ComponentBase;
use Lang;
use NumenCode\Fundamentals\Traits\ComponentRenderer;
use NumenCode\Widgets\Models\WidgetGroup;
use NumenCode\Widgets\Plugin;

/**
 * Base CMS component that displays one widget group with its published items.
 *
 * Properties:
 * - group:  ID of the displayed group (dropdown of all groups)
 * - layout: the layout partial (see NumenCode.Fundamentals ComponentRenderer); themes can add
 *           layouts in themes/<theme>/partials/<component class>/, e.g. partials/promotions/slider.htm
 *
 * Templates use {{ __SELF__.group }} and {{ __SELF__.group.items }}. Nothing is rendered when no group is
 * selected, or when the group doesn't exist or is unpublished (backend users still see unpublished groups).
 *
 * A widget type sets its group model; its translations are in the lang section named after the
 * component class (e.g. "promotions" for Promotions):
 *
 *     class Promotions extends GroupComponent
 *     {
 *         public static bool $overrideLayout = true;
 *
 *         protected string $groupModel = PromotionGroup::class;
 *     }
 *
 * Each component declares $overrideLayout itself, so it can be switched off per component.
 */
abstract class GroupComponent extends ComponentBase
{
    use ComponentRenderer {
        onRender as renderSelectedLayout;
    }

    /**
     * The displayed group, or null.
     */
    public ?WidgetGroup $group = null;

    /**
     * Class of the group model.
     *
     * @var class-string<WidgetGroup>
     */
    protected string $groupModel;

    /**
     * The icon is the widget type's backend menu icon (used by NumenCode.Bricks for the brick's icon).
     */
    public function componentDetails(): array
    {
        $details = [
            'name'        => $this->lang('name'),
            'description' => $this->lang('description'),
        ];

        if ($icon = Plugin::WIDGET_TYPES[strtolower(class_basename($this))] ?? null) {
            $details['icon'] = $icon;
        }

        return $details;
    }

    public function defineProperties(): array
    {
        return [
            'group'  => [
                'title'       => 'numencode.widgets::lang.component.group',
                'description' => 'numencode.widgets::lang.component.group_description',
                'type'        => 'dropdown',
                // Winter translates only title, description, options, group and validationMessage
                'placeholder' => Lang::get('numencode.widgets::lang.component.group_placeholder'),
                'default'     => '',
                'validation'  => [
                    'required' => ['message' => Lang::get('numencode.widgets::lang.component.group_required')],
                ],
            ],
            'layout' => [
                'title'       => 'numencode.widgets::lang.component.layout',
                'description' => 'numencode.widgets::lang.component.layout_description',
                'type'        => 'dropdown',
                'default'     => '@default',
            ],
        ];
    }

    /**
     * Options of the "group" property.
     *
     * @return array<int|string, string>
     */
    public function getGroupOptions(): array
    {
        return $this->groupModel::getGroupOptions();
    }

    public function onRun()
    {
        $this->group = $this->loadGroup();
    }

    /**
     * Renders the selected layout, or nothing without a group.
     */
    public function onRender()
    {
        if ($this->group === null) {
            // A non-empty string, otherwise the CMS renders the default partial
            return ' ';
        }

        return $this->renderSelectedLayout();
    }

    /**
     * Where the selected group is edited, for NumenCode.Bricks: a component brick shows an "Edit …" link to this
     * backend URL, with {group} replaced by the selected group. Null when the backend user may not manage this
     * widget type (no link then).
     */
    public static function bricksEditUrl(): ?string
    {
        $code = strtolower(class_basename(static::class));
        $user = BackendAuth::getUser();

        if (!$user || !$user->hasAccess('numencode.widgets.manage_' . $code)) {
            return null;
        }

        return 'numencode/widgets/' . $code . '/update/{group}';
    }

    /**
     * Loads the selected group with its items; unpublished groups and items are hidden by Publishable.
     */
    protected function loadGroup(): ?WidgetGroup
    {
        $id = $this->property('group');

        if (!is_numeric($id)) {
            return null;
        }

        return $this->groupModel::with('items')->find((int) $id);
    }

    /**
     * Translation key in this widget type's lang section.
     */
    protected function lang(string $key): string
    {
        return 'numencode.widgets::lang.' . strtolower(class_basename($this)) . '.' . $key;
    }
}
