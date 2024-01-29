<?php

namespace app\models\BitrixCrm\Models;

class ContactModel extends CrmBaseModel
{
    public int $id;
    public $name;
    public $secondName;
    public $lastName;
    public $phone = [];
    public $email = [];
    public $companyId;

    protected $company;

    public static function mapField()
    {
        return [
            'ID' => 'id',
            'NAME' => 'name',
            'SECOND_NAME' => 'secondName',
            'LAST_NAME' => 'lastName',
            'PHONE' => 'phone',
            'EMAIL' => 'email',
            'COMPANY_ID' => 'companyId',
        ];
    }

    public function setCompany(CrmBaseModel $companyModel)
    {
        $this->company = $companyModel;

        return $this;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function isExistOwnerCompany():bool
    {
        return !empty($this->companyId);
    }

    public function getFirstPhone()
    {
        return collect($this->phone)->get(0);
    }

    public function getFirstEmail()
    {
        return collect($this->email)->get(0);
    }
}
