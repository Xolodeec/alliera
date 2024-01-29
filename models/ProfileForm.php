<?php

namespace app\models;

use app\models\Entity\Contact;
use yii\base\Model;
use yii\web\IdentityInterface;

class ProfileForm extends Model
{
    public $id;
    public $name;
    public $emailId;
    public $email;
    public $phone;
    public $phoneId;
    public $password;
    public $repeatPassword;

    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['id', 'emailId', 'phoneId'], 'number'],
            [['name', 'password', 'phone'], 'string'],
            ['email', 'email'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'operator' => '==', 'message' => 'Пароли должны совпадать'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Email',
            'phone' => 'Телефон',
            'password' => 'Пароль',
            'repeatPassword' => 'Повторите пароль',
        ];
    }

    public static function instanceByIdentity(IdentityInterface $user)
    {
        $model = new static();
        $model->id = $user->id;
        $model->name = $user->name;

        $phone = collect($user->getFirstPhone());
        $email = collect($user->getFirstEmail());

        $model->emailId = $email->get('ID');
        $model->email = $email->get('VALUE');
        $model->phoneId = $phone->get('ID');
        $model->phone = $phone->get('VALUE');

        return $model;
    }

    public function save()
    {
        $contact = new Contact();
        $contact->id = $this->id;
        $contact->name = $this->name;
        $contact->email = [['ID' => $this->emailId, 'VALUE' => $this->email]];
        $contact->phone = [['ID' => $this->phoneId, 'VALUE' => $this->phone]];
        $contact->setPassword($this->password);

        return \Yii::$app->bitrix->contacts()->update($contact);
    }
}
