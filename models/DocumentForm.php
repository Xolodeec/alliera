<?php

namespace app\models;

use app\models\BitrixCrm\Models\CrmBaseModel;
use yii\base\Model;

class DocumentForm extends Model
{
    public $name;
    public $document;

    protected $fullPath;

    public function rules()
    {
        return [
            [['name'], 'string'],
            [['name'], 'required'],
            [['document'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpeg, png, pdf, doc, docx, txt'],
            [['document'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название документа',
            'document' => 'Документ',
        ];
    }

    public function uploadDocument()
    {
        if($this->validate())
        {
            $this->fullPath = \Yii::getAlias('@app') . "/web/src/documents/{$this->document->baseName}.{$this->document->extension}";
            $this->document->saveAs($this->fullPath);

            return true;
        }

        return false;
    }

    public function addDocument(CrmBaseModel $author)
    {
        $commands['upload_document'] = \Yii::$app->bitrix->api()->buildCommand('disk.folder.uploadfile', [
            'id' => 41,
            'data' => [
                'NAME' => "{$this->document->baseName}.{$this->document->extension}",
            ],
            'fileContent' => base64_encode(file_get_contents($this->fullPath)),
            'generateUniqueName' => true,
        ]);

        $commands['add_document'] = \Yii::$app->bitrix->api()->buildCommand('lists.element.add', [
            'IBLOCK_TYPE_ID' => 'lists',
            'IBLOCK_ID' => 17,
            'ELEMENT_CODE' => uniqid(),
            'FIELDS' => [
                'NAME' => $this->name,
                'PROPERTY_70' => "CO_" . $author->getCompany()->id,
                'PROPERTY_84' => '$result[upload_document][FILE_ID]',
                'PROPERTY_90' => $author->id,
            ],
        ]);

        return \Yii::$app->bitrix->api()->batchRequest($commands);
    }
}