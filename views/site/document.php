<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Документы для вас';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper-block mt-3">
    <table class="default-table">
        <thead>
        <tr>
            <th class="text-center column-small">#</th>
            <th class="title-task">Название документа</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($documents)) : ?>
            <?php foreach($documents as $index => $document) : ?>
                <tr>
                    <td class="text-center"><span class="bg-blue"><?= $index + 1 ?><span></td>
                    <td class="title-task"><?= Html::a($document->name, \yii\helpers\Url::to(['upload-document', 'id' => $document->id])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td class="text-center" colspan="2">Ничего не найдено</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

