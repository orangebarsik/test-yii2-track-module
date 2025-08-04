<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\track\models\Track $model */

$this->title = 'Обновить посылку: ' . $model->track_number;
$this->params['breadcrumbs'][] = ['label' => 'Посылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->track_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="track-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
