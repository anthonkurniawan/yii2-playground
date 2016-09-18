<?php

namespace app\controllers;

class interfaceController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
