<?php namespace NumenCode\Widgets\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Gallery extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $bodyClass = 'compact-container';

    public $requiredPermissions = [
        'numencode.widgets.manage_gallery',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('NumenCode.Widgets', 'widgets', 'gallery');
    }
}
