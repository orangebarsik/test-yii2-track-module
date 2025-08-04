<?php

use app\modules\track\models\TrackLog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Лог изменений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-log-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
			[
				'attribute' => 'changed_at',
				'format' => 'datetime',
				'value' => function($model) {
					return $model->changed_at ?: null;
				},
				'filter' => false,
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
			[
				'attribute' => 'action',
				'format' => 'raw',
				'value' => function($model) {
					return Html::a(
						Html::encode($model->action),
						['view', 'id' => $model->id],
						['title' => 'Просмотр действия']
					);
				},
				'label' => 'Действие',
			],
            'user_id',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}',
			],
        ],
    ]); ?>


</div>
