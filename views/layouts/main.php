<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <script src="https://kit.fontawesome.com/78d57075c0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>


<header id="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="logo">
                    <a href="<?= \yii\helpers\Url::to('/') ?>"><img src="/src/alliera-logo.png" class="img-fluid"></a>
                </div>
            </div>
            <div class="col-auto d-flex align-items-center">
                <button class="navbar-toggler first-button" type="button">
                    <div class="animated-icon1"><span></span><span></span><span></span></div>
                </button>
            </div>
        </div>
    </div>
    <div class="collapse navbar-collapse">
        <nav class="navigation">
            <li class="row g-0">
                <div class="dropdown">
                    <a href="javascript:void(0);" class="dropbtn-profile"><?= Yii::$app->user->identity->name ?></a>
                    <div class="wrapper-profile">
                        <ul id="list">
                            <li><a href="<?= \yii\helpers\Url::to('/site/profile') ?>">Профиль</a></li>
                            <li><a href="<?= \yii\helpers\Url::to('/auth/main/logout') ?>">Выход</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="row g-0">
                <div class="col">
                    <p>Остаток по счету:</p>
                    <p><?= Yii::$app->user->identity->getCompany()->getAccountBalance() ?> ₽</p>
                </div>
            </li>
            <li class="row g-0">
                <div class="col">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropbtn-unpaidDocument">
                            <p>Расчеты с БЦ:</p>
                            <p><?= Yii::$app->user->identity->getCompany()->getSettlementAmount() ?> ₽</p>
                        </a>
                        <div class="wrapper-unpaidDocument">
                            <ul id="list">
                                <li>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Загружаю...
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
        </nav>
        <nav class="navigation">
            <ul>
                <li class="row g-0">
                    <div class="col counter-document">
                        <a href="<?= \yii\helpers\Url::to('/site/document') ?>">Документы для вас <span></span></a>
                    </div>
                </li>
                <li class="row g-0">
                    <div class="col">
                        <a href="<?= \yii\helpers\Url::to('/site/my-document') ?>">Документы от вас</a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</header>

<main id="main">
    <div class="inner">
        <div class="sidebar left">
            <div class="logo">
                <img src="/src/alliera-logo.png" class="img-fluid">
            </div>
            <nav class="navigation">
                <li class="row g-0">
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="dropbtn-profile"><?= Yii::$app->user->identity->name ?></a>
                        <div class="wrapper-profile">
                            <ul id="list">
                                <li><a href="<?= \yii\helpers\Url::to('/site/profile') ?>">Профиль</a></li>
                                <li><a href="<?= \yii\helpers\Url::to('/auth/main/logout') ?>">Выход</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li class="row g-0">
                    <div class="col">
                        <p>Остаток по счету:</p>
                        <p><?= Yii::$app->user->identity->getCompany()->getAccountBalance() ?> ₽</p>
                    </div>
                </li>
                <li class="row g-0">
                    <div class="col">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="dropbtn-unpaidDocument">
                                <p>Расчеты с БЦ:</p>
                                <p><?= Yii::$app->user->identity->getCompany()->getSettlementAmount() ?> ₽</p>
                            </a>
                            <div class="wrapper-unpaidDocument">
                                <ul id="list">
                                    <li>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Загружаю...
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </nav>

            <nav class="navigation">
                <ul>
                    <li class="row g-0">
                        <div class="col counter-document">
                            <a href="<?= \yii\helpers\Url::to('/site/document') ?>">Документы для вас <span></span></a>
                        </div>
                    </li>
                    <li class="row g-0">
                        <div class="col">
                            <a href="<?= \yii\helpers\Url::to('/site/my-document') ?>">Документы от вас</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="content">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget([
                    'homeLink' => ['label' => 'Главная', 'url' => '/'],
                    'links' => $this->params['breadcrumbs'],
                    'itemTemplate' => "<li>{link}</li><li>/</li> \n",
                ]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
</main>

<?php
if(!Yii::$app->user->isGuest){
    $this->registerJsFile('/js/counter.js', ['depends' => 'yii\web\JqueryAsset']);
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
