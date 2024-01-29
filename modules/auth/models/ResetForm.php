<?php

namespace app\modules\auth\models;

use app\models\bitrix\Bitrix;
use app\models\Entity\Contact;
use app\models\school\School;
use app\models\TelegramBot;
use Tightenco\Collect\Support\Collection;
use Yii;
use yii\base\Model;

class ResetForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'validationEmail'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }

    public function validationEmail($attribute)
    {
        $user = \Yii::$app->bitrix->contacts()->setItemModel(new Contact())->getByEmail($this->$attribute);

        if(empty($user->id))
        {
            $this->addError($attribute, 'Пользователь с таким номером не зарегистрирован.');
        }
    }

    public function reset()
    {
        $user = \Yii::$app->bitrix->contacts()->setItemModel(new Contact())->getByEmail($this->email);
        $user->generatePassword();

        Yii::$app->bitrix->contacts()->update($user);

        return true;
    }
}