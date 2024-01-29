<?php

namespace app\models\Entity;

use app\models\BitrixCrm\Models\CompanyModel;

class Company extends CompanyModel
{
    public $isExistContract;
    public $accountBalance = 0;
    public $settlementAmount = 0;

    public static function mapField()
    {
        $mapFields = collect(parent::mapField());
        $mapFields->put('UF_CRM_1637129344', 'isExistContract');
        $mapFields->put('UF_CRM_1634717739692', 'accountBalance');
        $mapFields->put('UF_CRM_1634718891268', 'settlementAmount');

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
}
