<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Classes;

use Backend;
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use BackendMenu;
use Lang;
use NumenCode\Fundamentals\Classes\CmsPermissions;
use Request;
use Response;

/**
 * Base backend controller of a widget type: a list of groups and a form with the group's items.
 *
 * A widget type only sets its code, which is used for the side menu item, the permission
 * (numencode.widgets.manage_{code}) and the URL:
 *
 *     class Promotions extends WidgetController
 *     {
 *         protected string $widgetType = 'promotions';
 *     }
 *
 * The create, update and delete actions respect NumenCode.Fundamentals CmsPermissions, e.g.
 * CmsPermissions::revokeDelete('publishers', Promotions::class) hides the delete buttons for that
 * user group and blocks the delete handlers. Without the update permission, a record opens in preview.
 */
abstract class WidgetController extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    /**
     * Code of the widget type, e.g. "promotions".
     */
    protected string $widgetType = '';

    public function __construct()
    {
        $this->requiredPermissions = ['numencode.widgets.manage_' . $this->widgetType];

        parent::__construct();

        // Shared toolbars: after the widget type's own folder (its partials take precedence), but before the
        // behaviors' default views, which the behaviors have already added
        $paths = $this->getViewPaths();
        array_splice($paths, 1, 0, [__DIR__ . '/widgetcontroller']);
        $this->viewPath = $paths;

        BackendMenu::setContext('NumenCode.Widgets', 'widgets', $this->widgetType);

        $this->bindEvent('page.beforeDisplay', fn (string $action, array $params) => $this->checkCmsPermissions($action, $params));
    }

    /**
     * Base URL of this widget type's backend pages.
     */
    public function getWidgetUrl(string $path = ''): string
    {
        return Backend::url('numencode/widgets/' . $this->widgetType . ($path !== '' ? '/' . $path : ''));
    }

    /**
     * Denies the actions revoked with CmsPermissions, before the page or the AJAX handler runs.
     *
     * @param array<int, mixed> $params
     */
    protected function checkCmsPermissions(string $action, array $params): mixed
    {
        $isAjax = Request::ajax();
        $handler = $isAjax ? (string) $this->getAjaxHandler() : '';
        $handler = str_contains($handler, '::') ? '' : $handler; // widget handlers (e.g. a repeater's) are not actions

        $allowed = match (true) {
            $action === 'create'                            => CmsPermissions::canCreate($this),
            $action === 'update' && $handler === 'onSave'   => CmsPermissions::canUpdate($this),
            $handler === 'onDelete'                         => CmsPermissions::canDelete($this),
            default                                         => true,
        };

        if (!$allowed) {
            // Like the core's response to AJAX requests without access
            if ($isAjax) {
                return Response::make(Lang::get('backend::lang.page.access_denied.label'), 403);
            }

            abort(403);
        }

        // Without the update permission, a record can only be viewed (page loads only, not AJAX requests)
        if ($action === 'update' && !$isAjax && !CmsPermissions::canUpdate($this) && isset($params[0])) {
            return Backend::redirect('numencode/widgets/' . $this->widgetType . '/preview/' . $params[0]);
        }

        return null;
    }
}
