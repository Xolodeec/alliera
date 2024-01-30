<?php

namespace app\modules\auth_bitrix\models;

use app\models\Client as GeneralClient;

class Client extends GeneralClient
{
    protected static function getConfigPath():?string
    {
        return \Yii::getAlias('@app') . '/modules/auth_bitrix/config/config.php';
    }
}