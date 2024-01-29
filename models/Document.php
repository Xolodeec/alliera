<?php

namespace app\models;

use app\models\BitrixCrm\Client\Client;
use Tightenco\Collect\Support\Collection;

class Document
{
    public $name;
    public $id;

    public static function getListMyDocument($authorId)
    {
        $documentsCollection = new Collection();

        $documents = \Yii::$app->bitrix->api()->request('lists.element.get', [
            'IBLOCK_TYPE_ID' => 'lists',
            'IBLOCK_ID' => 17,
            'ELEMENT_ORDER' => ['ID' => 'DESC'],
            'FILTER' => [
                'PROPERTY_70' => "CO_" . $authorId,
            ],
        ]);

        foreach ($documents as $document)
        {
            $model = new static();
            $model->name = $document['NAME'];
            $model->id = collect($document['PROPERTY_84'])->keys()->get(0);

            $documentsCollection->push($model);
        }

        return $documentsCollection->toArray();
    }

    public static function getListDocumentCompany($authorId)
    {
        $documentsCollection = new Collection();

        $documents = \Yii::$app->bitrix->api()->request('lists.element.get', [
            'IBLOCK_TYPE_ID' => 'lists',
            'IBLOCK_ID' => 18,
            'ELEMENT_ORDER' => ['ID' => 'DESC'],
            'FILTER' => [
                'PROPERTY_71' => "CO_" . $authorId,
            ],
        ]);

        foreach ($documents as $document)
        {
            $model = new static();
            $model->name = $document['NAME'];
            $model->id = collect($document['PROPERTY_83'])->keys()->get(0);

            $documentsCollection->push($model);
        }

        return $documentsCollection->toArray();
    }
}