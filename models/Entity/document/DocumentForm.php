<?php

namespace app\models\Entity\document;

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
        $document = new Document();
        $document->entityTypeId = 190;
        $document->title = $this->name;
        $document->contactId = $author->id;
        $document->companyId = $author->getCompany()->id;
        $document->setDocument("{$this->document->baseName}.{$this->document->extension}", base64_encode(file_get_contents($this->fullPath)));

        $commands['create_items'] = \Yii::$app->bitrix->api()->buildCommand('crm.item.add', ['entityTypeId' => $document->entityTypeId, 'fields' => $document->collectFieldValue()]);
        $commands['start_bizproc'] = \Yii::$app->bitrix->api()->buildCommand('bizproc.workflow.start', [
            'TEMPLATE_ID' => 201,
            'DOCUMENT_ID' => ['crm', 'Bitrix\Crm\Integration\BizProc\Document\Dynamic', 'DYNAMIC_190_$result[create_items][item][id]'],
            'PARAMETERS' => ['COMPANY_ID' => "CO_{$author->getCompany()->id}"],
        ]);

        return \Yii::$app->bitrix->api()->batchRequest($commands);
    }
}