<?php

namespace app\models\BitrixCrm\EntitiesServices;

use app\models\BitrixCrm\Client\Client;
use app\models\BitrixCrm\Collection\CollectionContacts;
use app\models\BitrixCrm\Models\CompanyModel;
use app\models\BitrixCrm\Models\ContactModel;
use app\models\BitrixCrm\Models\CrmBaseModel;
use Tightenco\Collect\Support\Collection;

class Contacts extends BaseService
{
    protected $request;
    protected $collectionClass = CollectionContacts::class;
    protected $itemClass = ContactModel::class;

    public function getOne(int $id)
    {
        $data = $this->request->request('crm.contact.get', ['ID' => $id]);

        $class = $this->itemClass;
        $contactModel = new $class;

        return $contactModel->fromArray($data);
    }

    public function getOneWithCompany(int $id, CrmBaseModel $companyModel = null)
    {
        if(is_null($companyModel)){
            $companyModel = new CompanyModel();
        }

        $commands['get_contact'] = $this->request->buildCommand('crm.contact.get', ['ID' => $id]);
        $commands['get_company'] = $this->request->buildCommand('crm.company.get', ['ID' => '$result[get_contact][COMPANY_ID]']);

        ['result' => $data] = $this->request->batchRequest($commands);

        $contactModel = new $this->itemClass;
        $contactModel->setCompany($companyModel);

        if(isset($data['get_company'])){
            $contactModel->setCompany($companyModel->fromArray($data['get_company']));
        }

        return $contactModel->fromArray($data['get_contact']);
    }

    public function getFields()
    {
        return $this->request->request('crm.contact.fields');
    }

    public function getList(array $params = [])
    {
        $data = $this->request->request('crm.contact.list', $params);

        return $this->createCollection($data);
    }

    public function getByPhone(string $phone)
    {
        $class = $this->itemClass;
        $contactModel = new $class;

        $phone = preg_replace('/[^0-9]/', '', $phone);

        $data = $this->request->request('crm.duplicate.findbycomm', [
            'type' => 'PHONE',
            'values' => [$phone],
            'entity_type' => 'CONTACT',
        ]);

        if(!empty($data)){
            return $this->getOne($data['CONTACT'][0]);
        }

        return $contactModel;
    }

    public function getByEmail(string $email)
    {
        $class = $this->itemClass;
        $contactModel = new $class;

        $data = $this->request->request('crm.duplicate.findbycomm', [
            'type' => 'EMAIL',
            'values' => [$email],
            'entity_type' => 'CONTACT',
        ]);

        if(!empty($data)){
            return $this->getOne($data['CONTACT'][0]);
        }

        return $contactModel;
    }

    public function update(CrmBaseModel $contact)
    {
        $client = new Client();

        return $client->api()->request('crm.contact.update', ['ID' => $contact->id, 'fields' => $contact->collectFieldValue()]);
    }

    public function multipleUpdate(CollectionContacts $collectionContacts)
    {
        $commands = new Collection();
        $client = new Client();

        foreach ($collectionContacts as $contact){
            $commands->push($client->api()->buildCommand('crm.contact.update', ['ID' => $contact->id, 'fields' => $contact->collectFieldValue()]));
        }

        return $client->api()->batchRequest($commands->toArray());
    }
}
