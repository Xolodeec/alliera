<?php

namespace app\models\Entity\document;

use app\models\BitrixCrm\Models\ItemModel;

class Document extends ItemModel
{
    protected $document;

    public static function mapField()
    {
        $mapFields = collect(parent::mapField());
        $mapFields->put('ufCrm3_1706557018432', 'document');
        $mapFields->put('ufCrm4_1706560935680', 'document');

        return $mapFields->toArray();
    }

    public function setDocument(string $name, $fileContent)
    {
        $this->document = [$name, $fileContent];

        return $this;
    }

    public function getDownloadLink()
    {
        return collect($this->document)->get('urlMachine');
    }
}
