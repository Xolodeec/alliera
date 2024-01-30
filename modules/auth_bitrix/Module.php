<?php

namespace app\modules\auth_bitrix;

/**
 * auth-bitrix module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\auth_bitrix\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        \Yii::$app->params['modules']['auth_bitrix']['token'] = 'a00a618c-fb62-4462-9bcd-3e1c77e40396';
    }
}
