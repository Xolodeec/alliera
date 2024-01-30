<?php

namespace app\models\Entity;

use app\models\BitrixCrm\Models\CompanyModel;
use GuzzleHttp\Client;
use Tightenco\Collect\Support\Collection;

class Company extends CompanyModel
{
    public $isExistContract;
    public $accountBalance = 0;
    public $settlementAmount = 0;

    protected $unpaidBillDocuments;

    public static function mapField()
    {
        $mapFields = collect(parent::mapField());
        $mapFields->put('UF_CRM_1637129344', 'isExistContract');
        $mapFields->put('UF_CRM_1634717739692', 'accountBalance');
        $mapFields->put('UF_CRM_1634718891268', 'settlementAmount');
        $mapFields->put('UF_CRM_1634718945571', 'unpaidBillDocuments');

        return $mapFields->toArray();
    }

    public function isExistContract():bool
    {
        return $this->isExistContract == 1;
    }

    public function generatePassword()
    {
        foreach ($this->contacts as $index => $contact){
            $contact->generatePassword();
        }

        return $this;
    }

    public function getAccountBalance()
    {
        $filteredValue = preg_replace('/[^0-9]/', '', $this->accountBalance);

        return number_format($filteredValue, 2, ',', ' ');
    }

    public function getSettlementAmount()
    {
        $filteredValue = preg_replace('/[^0-9]/', '', $this->settlementAmount);

        return number_format($filteredValue, 2, ',', ' ');
    }

    public function getUnpaidBillDocuments()
    {
        if(!empty($this->unpaidBillDocuments)){

            $client = new Client();
            $app = \app\modules\auth_bitrix\models\Client::instance();

            $unpaidBillDocuments = new Collection();

            foreach ($this->unpaidBillDocuments as $file) {

                ['path' => $path, 'query' => $query] = parse_url($file['downloadUrl']);

                parse_str($query, $query);

                $query['auth'] = $app->access_token;

                $url = "https://portal.alliera.ru{$path}?" . http_build_query($query);

                $response = $client->request('GET', $url);

                $headers = $response->getHeaders();
                $contentDeposition = $headers['Content-Disposition'][0];

                preg_match('/filename="(.*?)";/', $contentDeposition, $matches);

                $fileName = $matches[1];
                $filePath = \Yii::getAlias('@app') . "/web/src/unpaid_documents/{$fileName}";
                $fileUrl = "https://test02.uchetprosto.ru/src/unpaid_documents/{$fileName}";

                file_put_contents($filePath,  $response->getBody()->getContents());

                $unpaidBillDocuments->put($fileName, $fileUrl);
            }

            return $unpaidBillDocuments->toArray();
        }

        return [];
    }
}
