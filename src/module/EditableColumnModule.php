<?php
namespace vsk\editableColumn\module;


use yii\base\Module;

class EditableColumnModule extends Module
{
    public $controllerMap = [
        'index' => IndexController::class
    ];
}