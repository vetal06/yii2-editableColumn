<?php
namespace vsk\editableColumn\module\controllers;

use vsk\editableColumn\EditableColumn;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use \yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class EditController
 */
class EditController extends Controller
{

    /**
     * @param $id
     */
    public function actionIndex($id)
    {
        /**
         * @var $modal ActiveRecord
         */

       $modelClassEncode = \Yii::$app->request->post('class');
       $attribute = \Yii::$app->request->post('attribute');
       \Yii::$app->response->format = Response::FORMAT_JSON;
       if (empty($modelClassEncode)) {
           throw new \yii\web\BadRequestHttpException('class is empty!');
       }
       $modelClass = base64_decode($modelClassEncode);
       if (!class_exists($modelClass)) {
           throw new \yii\web\BadRequestHttpException('class not valid!');
       }
       $query = call_user_func([$modelClass, 'find']);
       $modal = $query->andWhere(['id' => $id])->one();
       if (empty($modal)) {
           throw new NotFoundHttpException('Model Not found!');
       }
       $modal->setScenario(EditableColumn::MODEL_SCENARIO_EDITABLE_COLUMN);
       $scenarioAttributes = $this->getScenarionAttributes($modal);
       if (!in_array($attribute,$scenarioAttributes)) {
           throw new BadRequestHttpException('This attribute not set in you model rules scenario');
       }
       $modal->load(\Yii::$app->request->post());
       if ($modal->save(true, [$attribute])) {
           return ['output'=>$modal->getAttribute($attribute), 'message'=>''];
       } else {
           return ['output'=>'', 'message'=> $modal->getFirstError($attribute)];
       }
    }

    /**
     * @param ActiveRecord $model
     * @return mixed
     */
    protected function getScenarionAttributes(ActiveRecord $model)
    {
        $scenarios = [];
        foreach ($model->getValidators() as $validator) {
            foreach ($validator->on as $name) {
                foreach ($validator->attributes as $attribute) {
                    $scenarios[$name] = [$attribute];
                }
            }
        }
        return ArrayHelper::getValue($scenarios, EditableColumn::MODEL_SCENARIO_EDITABLE_COLUMN);
    }
}