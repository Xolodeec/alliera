<?php

/** @var yii\web\View $this */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Мой профиль';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
    'fieldConfig' => [
        'enableClientValidation' => false,
        'template' => "{label}{input}",
    ],
]) ?>
<?= $form->field($model, 'name', ['options' => ['class' => 'mb-3']])->textInput(); ?>
<?= $form->field($model, 'email', ['options' => ['class' => 'mb-3']])->textInput(); ?>
<?= $form->field($model, 'phone', ['options' => ['class' => 'mb-3']])->widget(\yii\widgets\MaskedInput::class, [
    'mask' => '+7 (999) 999 99 99',
]); ?>

<?= $form->field($model, 'password', ['options' => ['class' => 'mb-3']])->textInput([ 'type' => 'password']); ?>
<?= $form->field($model, 'repeatPassword', ['options' => ['class' => 'mb-3']])->textInput(['type' => 'password']); ?>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary w-100']) ?>

<?php $form::end() ?>