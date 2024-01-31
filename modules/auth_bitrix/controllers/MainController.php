<?php

namespace app\modules\auth_bitrix\controllers;

use app\models\logger\DebugLogger;
use yii\web\Controller;
use app\modules\auth_bitrix\models\Client;

class MainController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout = 'main';

    public function runAction($id, $params = [])
    {
        $token = \Yii::$app->params['modules']['auth_bitrix']['token'] ?? null;
        $requestToken = \Yii::$app->request->get('token');

        if(empty($requestToken) || $token !== $requestToken)
        {
            return self::actionAccessDenied();
        }

        return parent::runAction($id, $params); // TODO: Change the autogenerated stub
    }

    public function actionIndex()
    {
        $client = Client::instance(\Yii::$app->request->post());
        $client->refreshToken();

        return $this->render('index');
    }

    public function actionInstall()
    {
        $logger = DebugLogger::instance('install');
        $logger->save(\Yii::$app->request->post(), \Yii::$app->request->post(), 'POST Данные');

        if(\Yii::$app->request->isPost) {
            $client = Client::instance(\Yii::$app->request->post()['auth']);
            $client->updateConfig();
        }

        return $this->render('install');
    }

    public function actionAccessDenied()
    {
        return $this->render("access_denied");
    }
}
