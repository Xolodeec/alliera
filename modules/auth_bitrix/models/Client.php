<?php

namespace app\modules\auth_bitrix\models;

use App\HTTP\HTTP;
use Yii;

class Client
{
    public $access_token;
    public $refresh_token;
    public $client_endpoint;
    public $http;
    public $client_id;
    public $client_secret;

    public static function instance($params = [])
    {
        $model = new static();

        if(is_null(static::getConfigPath()))
        {
            throw new \Exception('Не указан путь до файла конфига');
        }

        $appsConfig = require static::getConfigPath();

        $model->http = new HTTP();
        $model->http->throttle = 2;
        $model->http->useCookies = false;

        $params = collect($params)->mapWithKeys(function ($item, $key){
            return [mb_strtolower($key) => $item];
        });

        if($params->has("auth_id"))
        {
            $model->access_token = $params["auth_id"] ?? $appsConfig["Доступы"]["access_token"];
            $model->refresh_token = $params["refresh_id"] ?? $appsConfig["Доступы"]["refresh_token"];
        }
        else
        {
            $model->access_token = $params["access_token"] ?? $appsConfig["Доступы"]["access_token"];
            $model->refresh_token = $params["refresh_token"] ?? $appsConfig["Доступы"]["refresh_token"];
        }

        $model->client_id = $appsConfig["Доступы"]["client_id"];
        $model->client_secret = $appsConfig["Доступы"]["client_secret"];
        $model->client_endpoint = 'https://portal.alliera.ru/rest/';

        return $model;
    }

    protected static function getConfigPath():?string
    {
        return \Yii::getAlias('@app') . '/modules/auth_bitrix/config/config.php';
    }

    public function request($method, $params = [])
    {
        $url = "{$this->client_endpoint}/{$method}.json";
        $params["auth"] = $this->access_token;

        $response = $this->http->request($url, "POST", $params);

        if(isset($response["error"]) && $response["error"] == "expired_token")
        {
            $this->refreshToken();

            $response = $this->request($method, $params);
        }

        return $response;
    }

    public function refreshToken():void
    {
        $params = [
            "grant_type" => "refresh_token",
            "client_id" => $this->client_id,
            "client_secret" => $this->client_secret,
            "refresh_token" => $this->refresh_token,
        ];

        $response = $this->http->request("https://oauth.bitrix.info/oauth/token/", "POST", $params);

        $this->access_token = $response['access_token'];
        $this->refresh_token = $response['refresh_token'];
    }

    public function updateConfig()
    {
        $appsConfig = require static::getConfigPath();

        if(!empty($appsConfig))
        {
            foreach ($appsConfig["Доступы"] as $key => &$value)
            {
                if(property_exists($this, $key))
                {
                    $appsConfig["Доступы"][$key] = $this->$key;
                }
            }

            $appsConfig = var_export($appsConfig, true);

            file_put_contents(static::getConfigPath(), "<?php\n return {$appsConfig};\n");
        }
    }

    public function buildCommand($method, $params = [])
    {
        $command = "{$method}";

        if(!empty($params))
        {
            $command .= "?" . http_build_query($params);
        }

        return $command;
    }

    public function batchRequest($commands, $halt = true)
    {
        $url = "{$this->client_endpoint}/batch";

        $response = $this->http->request($url, "POST", ["cmd" => $commands, "halt" => $halt, 'auth' => $this->access_token]);


        if(isset($response["error"]) && $response["error"] == "expired_token")
        {
            $this->refreshToken();

            $response = $this->batchRequest($commands, $halt);
        }

        return $response;
    }
}