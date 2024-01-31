<?php

namespace app\models;

use app\models\Entity\Company;
use app\models\Entity\Contact;

class User extends Contact implements \yii\web\IdentityInterface
{
    public $authKey;

    public static function findIdentity($id)
    {
        $user = new static();

        $contact = \Yii::$app->bitrix->contacts()->setItemModel(new Contact())->getOneWithCompany($id, new Company());
        $user->setCompany($contact->getCompany());

        return $user->fromArray($contact->collectFieldValue());
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByEmail(string $email)
    {
        $user = new static();

        $contact = \Yii::$app->bitrix->contacts()->setItemModel(new Contact())->getByEmail($email);

        return $user->fromArray($contact->collectFieldValue());
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return !empty($this->password) && \Yii::$app->security->validatePassword($password, $this->password);
    }
}
