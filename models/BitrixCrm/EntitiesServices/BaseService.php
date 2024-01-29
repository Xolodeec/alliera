<?php


namespace app\models\BitrixCrm\EntitiesServices;

use app\models\BitrixCrm\Collection\CollectionItems;
use app\models\BitrixCrm\Models\ContactModel;
use app\models\BitrixCrm\Models\CrmBaseModel;

abstract class BaseService
{
    protected $request;
    protected $collectionClass;
    protected $itemClass;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function setItemModel(CrmBaseModel $class)
    {
        $this->itemClass = get_class($class);

        return $this;
    }

    public function setCollectionModel(CollectionItems $class)
    {
        $this->collectionClass = get_class($class);

        return $this;
    }

    public function createCollection(array $contacts)
    {
        $collectionClass = $this->collectionClass;
        $collectionModel = new $collectionClass();

        foreach ($contacts as $contact) {
            $contactClass = $this->itemClass;
            $contactModel = (new $contactClass)->fromArray($contact);

            $collectionModel->push($contactModel);
        }

        return $collectionModel;
    }
}
