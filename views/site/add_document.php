<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Добавить документ';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'document-form',
    'fieldConfig' => [
        'enableClientValidation' => false,
        'template' => "{label}{input}{error}",
    ],
]) ?>
<?= $form->field($model, 'name', ['options' => ['class' => 'mb-3']])->textInput(); ?>
<?= $form->field($model, 'document', ['options' => ['class' => 'mb-3']])->fileInput(); ?>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary w-100']) ?>

<?php $form::end() ?>
