<?php namespace NumenCode\Widgets\Controllers;

use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use BackendMenu;
use Backend\Classes\Controller;

class Promotions extends Controller
{
    public $implement = [
        ListController::class,
        FormController::class,
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'manage_promotions_groups',
        'manage_promotions_items',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('NumenCode.Widgets', 'widgets', 'promotions');
    }
}
