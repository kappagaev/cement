<?php

namespace backend\controllers;

use common\models\Import;
use common\models\one_c\Product;
use common\services\one_c\models\Export;
use Yii;
use yii\helpers\VarDumper;

class ApiController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
    }

    public function actionImport()
    {
        $request = Yii::$app->request;
        $data = json_decode(($request->getRawBody()), true);
        foreach ($data as $modelJson) {
            $import = new Import();
            $import->attributes = $modelJson;
            $import->data = json_encode($modelJson['data']);
            $import->save();
            $import->process();
        }
    }

    public function actionExport(): \yii\web\Response
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $exports = array_map(function ($export) {
            $export = $export->toArray();
            $export['data'] = json_decode($export['data']);
            return $export;
        }, Export::find()->where(['>=', 'id', $id])->all());

        return $this->asJson($exports);
    }


}
