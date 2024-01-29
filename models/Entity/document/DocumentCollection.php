<?php

namespace app\models\Entity\document;

use app\models\BitrixCrm\Collection\CollectionItems;

class DocumentCollection extends CollectionItems
{
    public function resetCounter()
    {
        foreach ($this->items as &$document)
        {
            $document->stageId = 'DT177_7:PREPARATION';
        }

        return \Yii::$app->bitrix->items()->multipleUpdate($this);
    }
}
