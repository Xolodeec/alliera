<?php

namespace app\modules\auth\controllers;

use app\models\BitrixCrm\Client\Client;
use app\models\Entity\Company;
use app\models\Entity\Contact;
use app\models\logger\DebugLogger;
use yii\web\Controller;

class WebhookController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actionSignUpContact()
    {
        $logger = DebugLogger::instance('signup_contact');
        $logger->save(\Yii::$app->request->post(), \Yii::$app->request->post(), 'POST Данные');

        if(\Yii::$app->request->isPost) {
            $contactId = \Yii::$app->request->post()['data']['FIELDS']['ID'];
            $contact = \Yii::$app->bitrix->contacts()->setItemModel(new Contact())->getOneWithCompany($contactId, new Company());

            if(!$contact->isExistPassword() && $contact->getCompany()->isExistContract()){
                $contact->generatePassword();
                \Yii::$app->bitrix->contacts()->update($contact);
            }
        }

        return 200;
    }

    public function actionSignUpCompany()
    {
        $logger = DebugLogger::instance('signup_company');
        $logger->save(\Yii::$app->request->post(), \Yii::$app->request->post(), 'POST Данные');

        if(\Yii::$app->request->isPost){
            $companyId = \Yii::$app->request->post()['data']['FIELDS']['ID'];
            $company = \Yii::$app->bitrix->companies()->setItemModel(new Company())->getOneWithCompany($companyId, new Contact());

            if($company->isExistContract() && $company->getContacts()->isNotEmpty()){
                $company->generatePassword();
                \Yii::$app->bitrix->contacts()->multipleUpdate($company->getContacts());
            }
        }

        return 200;
    }
}