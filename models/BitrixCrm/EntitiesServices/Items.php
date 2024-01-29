<?php

namespace app\models\BitrixCrm\EntitiesServices;

use app\models\BitrixCrm\Collection\CollectionCompanies;
use app\models\BitrixCrm\Collection\CollectionItems;
use app\models\BitrixCrm\Models\CompanyModel;
use app\models\BitrixCrm\Models\ContactModel;
use app\models\BitrixCrm\Models\CrmBaseModel;
use app\models\BitrixCrm\Models\ItemModel;
use Tightenco\Collect\Support\Collection;

class Items extends BaseService
{
    protected $request;
    protected $collectionClass = CollectionItems::class;
    protected $itemClass = ItemModel::class;

    public function getOne(int $entityTypeId, int $id)
    {
        $data = $this->request->request('crm.item.get', ['entityTypeId' => $entityTypeId, 'id' => $id]);

        $class = $this->itemClass;
        $contactModel = new $class;

        return $contactModel->fromArray($data);
    }

    public function getFields(int $entityTypeId)
    {
        return $this->request->request('crm.item.fields', ['entityTypeId' => $entityTypeId]);
    }

    public function getList(array $params = [])
    {
        $data = $this->request->request('crm.item.list', $params);

        return $this->createCollection($data['items']);
    }

    public function create(CrmBaseModel $item)
    {
        return $this->request->request('crm.item.add', ['entityTypeId' => $item->entityTypeId, 'fields' => $item->collectFieldValue()]);
    }

    public function update(CrmBaseModel $item)
    {
        return $this->request->request('crm.item.update', ['entityTypeId' => $item->entityTypeId, 'id' => $item->id, 'fields' => $item->collectFieldValue()]);
    }

    public function multipleUpdate(CollectionItems $collectionItems)
    {
        $commands = new Collection();

        foreach ($collectionItems as $item){
            $commands->push($this->request->buildCommand('crm.item.update', ['entityTypeId' => $item->entityTypeId, 'id' => $item->id, 'fields' => $item->collectFieldValue()]));
        }

        return $this->request->batchRequest($commands->toArray());
    }
}
