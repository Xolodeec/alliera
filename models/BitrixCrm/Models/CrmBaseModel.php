<?php

namespace app\models\BitrixCrm\Models;

abstract class CrmBaseModel
{
    public static function mapField()
    {
        return [];
    }

    public function getPropertyField(string $fieldId):?string
    {
        return collect(static::mapField())->get($fieldId);
    }

    public function isDeclaredField(string $fieldId):bool
    {
        return !empty($this->getPropertyField($fieldId)) ?? $this->getPropertyField($fieldId);
    }

    public function fromArray(array $contact):self
    {
        foreach ($contact as $fieldId => $value){
            if($this->isDeclaredField($fieldId) && property_exists($this, $this->getPropertyField($fieldId))){
                $propertyName = $this->getPropertyField($fieldId);
                $this->$propertyName = $value;
            }
        }

        return $this;
    }

    public function collectFieldValue():array
    {
        $fields = [];
        $mapField = static::mapField();

        foreach ($mapField as $fieldId => $var)
        {
            if(!empty($this->$var)){
                $fields[$fieldId] = $this->$var;
            }
        }

        return $fields;
    }
}