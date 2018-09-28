<?php
namespace vsk\editableColumn;

use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class EditableColumn
 * @package vsk\editableColumn
 */
class EditableColumn extends \kartik\editable\Editable
{

    const MODEL_SCENARIO_EDITABLE_COLUMN = 'editableColumn';

    public $moduleName = 'editablecolumn';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function initEditable()
    {
        if (!$this->model instanceof ActiveRecord) {
            throw new Exception('Set model only instanceof ActiveRecord');
        }
        $this->formOptions = array_merge($this->formOptions, [
            'action' => Url::to(['/'.$this->moduleName.'/edit/index', 'id' => $this->model->id]),
        ]);
//        if (empty($this->options['id'])) {
        $this->options['id'] = $this->getId().uniqid();
//        }

        parent::initEditable();


    }

    /**
     *
     */
    public function registerAssets()
    {
        parent::registerAssets();
        $view = $this->getView();
        \kartik\editable\EditablePjaxAsset::register($view);
        $id = $this->_popoverOptions['toggleButton']['id'];
        $view->registerJs("initEditablePopover('$id'); ");
    }

    /**
     * @return string|void
     */
    protected function renderFormFields()
    {
        parent::renderFormFields();
        echo Html::hiddenInput('class',base64_encode(get_class($this->model))) . "\n";
        echo Html::hiddenInput('attribute', $this->attribute) . "\n";
    }
}