<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Tests\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use NumenCode\Fundamentals\Classes\CmsPermissions;
use NumenCode\Widgets\Classes\WidgetController;
use NumenCode\Widgets\Controllers\Counters;
use NumenCode\Widgets\Controllers\Promotions;
use NumenCode\Widgets\Plugin;
use NumenCode\Widgets\Tests\WidgetsTestCase;
use ReflectionMethod;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WidgetControllerTest extends WidgetsTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loginBackendUser('editors');
    }

    /**
     * Simulates a backend AJAX request to a handler.
     */
    protected function ajaxRequest(string $handler): void
    {
        $request = $this->app['request'];
        $request->setMethod('POST');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->headers->set('X-WINTER-REQUEST-HANDLER', $handler);
    }

    /**
     * @param array<int, mixed> $params
     */
    protected function check(WidgetController $controller, string $action, array $params = []): mixed
    {
        return (new ReflectionMethod($controller, 'checkCmsPermissions'))->invoke($controller, $action, $params);
    }

    public function testEveryWidgetTypeHasItsPermissionAndUrl(): void
    {
        foreach (self::TYPES as $code => [, , $class]) {
            $controller = new $class();

            $this->assertInstanceOf(WidgetController::class, $controller, $code);
            $this->assertSame(['numencode.widgets.manage_' . $code], (new \ReflectionProperty($controller, 'requiredPermissions'))->getValue($controller), $code);
            $this->assertStringEndsWith('/numencode/widgets/' . $code . '/create', $controller->getWidgetUrl('create'), $code);
        }
    }

    public function testSharedToolbarsComeBeforeTheBehaviorDefaults(): void
    {
        $paths = array_map(fn ($path) => str_replace('\\', '/', $path), (new Promotions())->getViewPaths());

        $this->assertStringEndsWith('controllers/promotions', $paths[0], 'The widget type\'s own partials win');
        $this->assertStringEndsWith('classes/widgetcontroller', $paths[1]);
    }

    public function testListToolbarShowsTheAllowedActions(): void
    {
        $html = (new Promotions())->makePartial('list_toolbar');

        $this->assertStringContainsString('Create promotion', $html);
        $this->assertStringContainsString('data-request="onDelete"', $html);

        CmsPermissions::revokeCreate('editors', Promotions::class);
        CmsPermissions::revokeDelete('editors', Promotions::class);
        $html = (new Promotions())->makePartial('list_toolbar');

        $this->assertStringNotContainsString('Create promotion', $html);
        $this->assertStringNotContainsString('onDelete', $html);
    }

    public function testAllowedActionsPass(): void
    {
        $controller = new Promotions();

        $this->assertNull($this->check($controller, 'index'));
        $this->assertNull($this->check($controller, 'create'));
        $this->assertNull($this->check($controller, 'update', [1]));
    }

    public function testRevokedCreateDeniesTheCreatePage(): void
    {
        CmsPermissions::revokeCreate('editors', Promotions::class);

        try {
            $this->check(new Promotions(), 'create');
            $this->fail('The create page must be denied');
        } catch (HttpException $e) {
            $this->assertSame(403, $e->getStatusCode());
        }

        $this->assertNull($this->check(new Counters(), 'create'), 'Other widget types are not affected');
    }

    public function testRevokedDeleteDeniesTheDeleteHandlers(): void
    {
        CmsPermissions::revokeDelete('editors', Promotions::class);

        foreach (['index', 'update'] as $action) {
            $this->ajaxRequest('onDelete');
            $response = $this->check(new Promotions(), $action, [1]);

            $this->assertInstanceOf(Response::class, $response, $action);
            $this->assertSame(403, $response->getStatusCode(), $action);
        }

        $this->ajaxRequest('onSave');
        $this->assertNull($this->check(new Promotions(), 'update', [1]), 'Saving is still allowed');
    }

    public function testRevokedUpdateOpensRecordsAsPreview(): void
    {
        CmsPermissions::revokeUpdate('editors', Promotions::class);

        $response = $this->check(new Promotions(), 'update', [5]);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertStringEndsWith('/numencode/widgets/promotions/preview/5', $response->getTargetUrl());

        $this->ajaxRequest('onSave');
        $this->assertSame(403, $this->check(new Promotions(), 'update', [5])->getStatusCode());

        // Form widget handlers (e.g. a repeater's) are not actions
        $this->ajaxRequest('formItemsList::onAddItem');
        $this->assertNull($this->check(new Promotions(), 'update', [5]));
    }

    public function testPluginRegistersAllWidgetTypes(): void
    {
        $plugin = new Plugin($this->app);
        $codes = array_keys(self::TYPES);

        $this->assertSame(['NumenCode.Fundamentals'], $plugin->require);
        $this->assertSame($codes, array_keys($plugin->registerNavigation()['widgets']['sideMenu']));
        $this->assertSame(
            array_map(fn ($code) => 'numencode.widgets.manage_' . $code, $codes),
            array_keys($plugin->registerPermissions())
        );

        foreach (self::TYPES as $code => [, , , $component]) {
            $this->assertSame($code, $plugin->registerComponents()[$component]);
            $this->assertSame($code, $plugin->registerPageSnippets()[$component]);
        }
    }
}
