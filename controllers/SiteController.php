<?php

namespace app\controllers;

use App\HTTP\HTTP;
use app\models\BitrixCrm\Client\Client;
use app\models\Document;
use app\models\DocumentForm;
use app\models\ProfileForm;
use Carbon\Carbon;
use Tightenco\Collect\Support\Collection;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
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
                        'actions' => ['index', 'profile', 'my-document', 'add-document', 'document', 'logout'],
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
        $documents = Document::getListMyDocument(Yii::$app->user->identity->getCompany()->id);

        return $this->render('my_document', ['documents' => $documents]);
    }

    public function actionDocument()
    {
        $documents = Document::getListDocumentCompany(Yii::$app->user->identity->getCompany()->id);

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

    public function actionAbout()
    {
        return $this->render('about');
    }
}
