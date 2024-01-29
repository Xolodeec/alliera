<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = "Сброс пароля";

?>

<div class="wrapper-form shadow-sm rounded mb-4">
    <h4 class="mb-4 text-center text-uppercase">Сброс пароля</h4>
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'fieldConfig' => [
            'enableClientValidation' => false,
            'template' => "{input}{label}",
        ],
    ]) ?>
    <?= $form->field($model, 'email', ['options' => ['class' => 'form-floating mb-3']])->textInput(['placeholder' => $model->getAttributeLabel('email')]); ?>
    <?= Html::submitButton('Сбросить', ['class' => 'btn btn-primary w-100']) ?>
    <?php $form::end() ?>
</div>

<div class="auth-toolbar text-center">
    <?= Html::a('Есть уже аккаунт?', 'login'); ?>
</div>
