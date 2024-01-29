<?php

namespace app\models\BitrixCrm\Client;

use App\HTTP\HTTP;

class Request
{
    public const THROTTLE = 0.5;
    public const CONNECT_TIMEOUT = 60;
    public const REQUEST_TIMEOUT = 60;

    protected $httpClient;
    protected $restUrl;

    public function __construct(string $restUrl)
    {
        $this->httpClient = new HTTP();
        $this->httpClient->throttle = self::THROTTLE;
        $this->httpClient->curlConnectTimeout = self::CONNECT_TIMEOUT;
        $this->httpClient->curlTimeout = self::REQUEST_TIMEOUT;
        $this->restUrl = $restUrl;
    }

    public function request(string $method, array $params = [])
    {
        $response = $this->httpClient->request("{$this->restUrl}/$method", 'POST', $params);

        return $response['result'];
    }

    public function buildCommand(string $method, array $params = [])
    {
        $command = "{$method}";

        if(!empty($params))
        {
            $command .= "?" . http_build_query($params);
        }

        return $command;
    }

    public function batchRequest(array $batch = [], $halt = true)
    {
        $response = $this->httpClient->request("{$this->restUrl}/batch", "POST", ["cmd" => $batch, "halt" => $halt]);

        return $response['result'];
    }
}