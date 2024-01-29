<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = "Авторизация";

?>


<div class="wrapper-form shadow-sm rounded mb-4">
    <h4 class="mb-4 text-center text-uppercase">Авторизация</h4>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'fieldConfig' => [
            'enableClientValidation' => false,
            //'template' => "<div class='row'><div class='col-3'>{label}</div><div class='col'>{input}</div></div>{error}",
            'template' => "{input}{label}",
        ],
    ]) ?>
    <?= $form->field($model, 'email', ['options' => ['class' => 'form-floating mb-3']])->textInput(['placeholder' => $model->getAttributeLabel('email')]); ?>
    <?= $form->field($model, 'password', ['options' => ['class' => 'form-floating mb-3']])->passwordInput(['placeholder' => $model->getAttributeLabel('password')]); ?>
    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary w-100']) ?>

    <?php $form::end() ?>
</div>

<div class="auth-toolbar text-center">
    <?= Html::a('Сбросить пароль', 'reset'); ?>
</div>

