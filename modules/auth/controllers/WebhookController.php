<?php

namespace app\modules\auth\controllers;

use app\models\BitrixCrm\Client\Client;
use app\models\Entity\Company;
use app\models\Entity\Contact;
use yii\web\Controller;

class WebhookController extends Controller
{
    public function actionSignUpContact()
    {
        $client = new Client();

        $contact = $client->contacts()->setItemModel(new Contact())->getOneWithCompany(8195, new Company());

        if(!$contact->isExistPassword() && $contact->getCompany()->isExistContract()){
            $contact->generatePassword();
            $client->contacts()->update($contact);
        }

        return 200;
    }

    public function actionSignUpCompany()
    {
        $client = new Client();

        $company = $client->companies()->setItemModel(new Company())->getOneWithCompany(8693, new Contact());

        if($company->isExistContract() && $company->getContacts()->isNotEmpty()){
            $company->generatePassword();
            $client->contacts()->multipleUpdate($company->getContacts());
        }

        return 200;
    }
}