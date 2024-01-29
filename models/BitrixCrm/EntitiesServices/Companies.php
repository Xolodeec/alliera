<?php

namespace app\models\BitrixCrm\EntitiesServices;

use app\models\BitrixCrm\Client\Client;
use app\models\BitrixCrm\Collection\CollectionCompanies;
use app\models\BitrixCrm\Models\CompanyModel;
use app\models\BitrixCrm\Models\ContactModel;
use app\models\BitrixCrm\Models\CrmBaseModel;
use Tightenco\Collect\Support\Collection;

class Companies extends BaseService
{
    protected $request;
    protected $collectionClass = CollectionCompanies::class;
    protected $itemClass = CompanyModel::class;

    public function getOne(int $int)
    {
        $data = $this->request->request('crm.company.get', ['ID' => $int]);

        $class = $this->itemClass;
        $contactModel = new $class;

        return $contactModel->fromArray($data);
    }

    public function getOneWithCompany(int $id, CrmBaseModel $contactModel = null)
    {
        if(is_null($contactModel)){
            $contactModel = new ContactModel();
        }

        $commands['get_company'] = $this->request->buildCommand('crm.company.get', ['ID' => $id]);
        $commands['get_contacts'] = $this->request->buildCommand('crm.contact.list', ['filter' => ['COMPANY_ID' => '$result[get_company][ID]']]);

        ['result' => $data] = $this->request->batchRequest($commands);

        $contactServiceModel = (new Contacts())->setItemModel($contactModel);

        $companyModel = new $this->itemClass;
        $companyModel->setContacts($contactServiceModel->createCollection([]));

        if(!empty($data['get_contacts'])){
            $companyModel->setContacts($contactServiceModel->createCollection($data['get_contacts']));
        }

        return $companyModel->fromArray($data['get_company']);
    }

    public function getFields()
    {
        return $this->request->request('crm.company.fields');
    }

    public function getList(array $params = [])
    {
        $data = $this->request->request('crm.company.list', $params);

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
            'entity_type' => 'COMPANY',
        ]);

        if(!empty($data)){
            return $this->getOne($data['COMPANY'][0]);
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
            'entity_type' => 'COMPANY',
        ]);

        if(!empty($data)){
            return $this->getOne($data['COMPANY'][0]);
        }

        return $contactModel;
    }

    public function update(CrmBaseModel $company)
    {
        $client = new Client();

        return $client->api()->request('crm.company.update', ['ID' => $company->id, 'fields' => $company->collectFieldValue()]);
    }

    public function multipleUpdate(CollectionCompanies $collectionCompanies)
    {
        $commands = new Collection();
        $client = new Client();

        foreach ($collectionCompanies as $company){
            $commands->push($client->api()->buildCommand('crm.company.update', ['ID' => $company->id, 'fields' => $company->collectFieldValue()]));
        }

        return $client->api()->batchRequest($commands->toArray());
    }
}
