<?php

namespace app\models\BitrixCrm\Client;

use app\models\BitrixCrm\EntitiesServices\BaseService;
use app\models\BitrixCrm\EntitiesServices\Companies;
use app\models\BitrixCrm\EntitiesServices\Contacts;
use app\models\BitrixCrm\EntitiesServices\Items;

class Client
{
    protected $restUrl;

    public function __construct(?string $restUrl = null)
    {
        $this->restUrl = $restUrl;

        if(empty($restUrl)){
            $this->restUrl = \Yii::$app->params['bitrixParams']['webhookUrl'];
        }
    }

    public function buildRequest()
    {
        $requestModel = new Request($this->restUrl);

        return $requestModel;
    }

    public function contacts()
    {
        $request = $this->buildRequest();

        return new Contacts($request);
    }

    public function items()
    {
        $request = $this->buildRequest();

        return new Items($request);
    }

    public function companies()
    {
        $request = $this->buildRequest();

        return new Companies($request);
    }

    public function api()
    {
        return $this->buildRequest();
    }
}