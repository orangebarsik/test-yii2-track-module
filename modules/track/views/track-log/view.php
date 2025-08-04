<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\track\models\TrackLog $model */

$this->title = 'Просмотр изменения: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Лог изменений', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="track-log-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
			[
				'attribute' => 'changed_at',
				'format' => 'datetime',
				'value' => function($model) {
					return $model->changed_at ?: null;
				}
			],
			[
				'attribute' => 'track_id',
				'format' => 'raw',
				'value' => function($model) {
					if ($model->track && $model->track->track_number) {
						return Html::a(
							Html::encode($model->track->track_number),
							['track/view', 'id' => $model->track_id],
							['title' => 'Просмотр посылки']
						);
					}
					return 'Не указан';
				},
				'label' => 'Номер трека',
			],
            'action',
            'old_attributes:ntext',
            'new_attributes:ntext',
            'user_id',
        ],
    ]) ?>

</div>
