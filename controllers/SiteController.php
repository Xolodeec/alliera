<?php

namespace app\controllers;

use app\models\BitrixCrm\Client\Client;
use app\models\BitrixCrm\Collection\CollectionItems;
use app\models\Entity\document\Document;
use app\models\Entity\document\DocumentCollection;
use app\models\Entity\document\DocumentForm;
use app\models\ProfileForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'profile', 'my-document', 'add-document', 'document', 'get-counter-document', 'reset-counter-document', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    if(Yii::$app->user->isGuest){
                        return $action->controller->redirect('/auth/main/login');
                    }

                    return $action->controller->redirect('/');
                },
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProfile()
    {
        $model = ProfileForm::instanceByIdentity(Yii::$app->user->identity);

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()){
            $model->save();
            return $this->refresh();
        }

        return $this->render('profile', ['model' => $model]);
    }

    public function actionMyDocument()
    {
        $documents = Yii::$app->bitrix
            ->items()
            ->setItemModel(new Document())
            ->getList([
                'entityTypeId' => 190,
                'order' => ['ID' => 'DESC'],
                'filter' => ['=companyId' => Yii::$app->user->identity->getCompany()->id]
            ]);

        return $this->render('my_document', ['documents' => $documents]);
    }

    public function actionDocument()
    {
        $documents = Yii::$app->bitrix
            ->items()
            ->setItemModel(new Document())
            ->getList([
                'entityTypeId' => 177,
                'order' => ['ID' => 'DESC'],
                'filter' => ['=companyId' => Yii::$app->user->identity->getCompany()->id]
            ]);

        return $this->render('document', ['documents' => $documents]);
    }

    public function actionAddDocument()
    {
        $model = new DocumentForm();

        if(\Yii::$app->request->isPost && $model->load(\Yii::$app->request->post()))
        {
            $model->document = UploadedFile::getInstance($model, 'document');

            if($model->uploadDocument())
            {
                $model->addDocument(Yii::$app->user->identity);

                return $this->redirect('my-document');
            }
        }

        return $this->render('add_document', ['model' => $model]);
    }

    public function actionGetCounterDocument()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $documents = Yii::$app->bitrix
            ->items()
            ->setItemModel(new Document())
            ->getList([
                'entityTypeId' => 177,
                'order' => ['ID' => 'DESC'],
                'filter' => ['=companyId' => Yii::$app->user->identity->getCompany()->id, '=ufCrm4_1706615956444' => "0"],
                'start' => '-1',
            ]);

        return $documents->count();
    }

    public function actionResetCounterDocument()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return Yii::$app->bitrix
            ->items()
            ->setItemModel(new Document())
            ->setCollectionModel(new DocumentCollection())
            ->getList([
                'entityTypeId' => 177,
                'order' => ['ID' => 'DESC'],
                'filter' => ['=companyId' => Yii::$app->user->identity->getCompany()->id, '=ufCrm4_1706615956444' => "0"]
            ])
            ->resetCounter();
    }
}
