<?php
namespace vsk\editableColumn\module\controllers;

use vsk\editableColumn\EditableColumn;
use yii\db\ActiveRecord;
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
       $modal->load(\Yii::$app->request->post());
       \Yii::$app->response->format = Response::FORMAT_JSON;
       if ($modal->save(true, [$attribute])) {
           return ['output'=>$modal->getAttribute($attribute), 'message'=>''];
       } else {
           return ['output'=>'', 'message'=> $modal->getFirstError($attribute)];
       }
    }
}