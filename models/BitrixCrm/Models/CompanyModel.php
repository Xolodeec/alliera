<?php

namespace app\models\BitrixCrm\Models;

use app\models\BitrixCrm\Collection\CollectionCompanies;
use app\models\BitrixCrm\Collection\CollectionContacts;

class CompanyModel extends CrmBaseModel
{
    public int $id;
    public $title;
    public $phone;
    public $email;
    public $contactId;

    protected $contacts;

    public static function mapField()
    {
        return [
            'ID' => 'id',
            'TITLE' => 'title',
            'PHONE' => 'phone',
            'EMAIL' => 'email',
            'CONTACT_ID' => 'contactId',
        ];
    }

    public function setContacts(CollectionContacts $collectionContacts)
    {
        $this->contacts = $collectionContacts;

        return $this;
    }

    public function getContacts()
    {
        return $this->contacts;
    }
}
