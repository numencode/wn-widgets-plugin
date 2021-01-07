<?php namespace NumenCode\Widgets\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;

class Promotions extends Controller
{
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $bodyClass = 'compact-container';

    public $requiredPermissions = [
        'numencode.widgets.manage_promotions',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('NumenCode.Widgets', 'widgets', 'promotions');
    }
}
