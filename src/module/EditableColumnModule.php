<?php
namespace vsk\editableColumn\module;


use vsk\editableColumn\module\controllers\EditController;

use yii\base\Module;

class EditableColumnModule extends Module
{
    public $controllerMap = [
        'index' => EditController::class
    ];
}