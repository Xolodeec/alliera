<?php

namespace app\models\Entity\document;

use app\models\BitrixCrm\Collection\CollectionItems;

class DocumentCollection extends CollectionItems
{
    public function resetCounter()
    {
        foreach ($this->items as &$document)
        {
            $document->isViewed = "true";
        }

        return \Yii::$app->bitrix->items()->multipleUpdate($this);
    }
}
