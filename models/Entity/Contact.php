<?php

namespace app\models\Entity;

use app\models\BitrixCrm\Models\ContactModel;

class Contact extends ContactModel
{
    protected $password;
    protected $decodedPassword;

    public static function mapField()
    {
        $mapFields = collect(parent::mapField());
        $mapFields->put('UF_CRM_1706188245102', 'password');
        $mapFields->put('UF_CRM_1706460207743', 'decodedPassword');

        return $mapFields->toArray();
    }

    public function isExistPassword():bool
    {
        return !empty($this->password);
    }

    public function generatePassword()
    {
        $this->decodedPassword = \Yii::$app->security->generateRandomString(6);
        $this->password = \Yii::$app->security->generatePasswordHash($this->decodedPassword);

        return $this;
    }

    public function setPassword(string $password)
    {
        $this->decodedPassword = $password;
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }
}