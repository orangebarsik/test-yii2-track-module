<?php

use app\modules\track\models\Track;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\modules\track\models\TrackSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Посылки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="track-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Добавить посылку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_merge([
			[
				'attribute' => 'track_number',
				'format' => 'raw',
				'value' => function($model) {
					return Html::a(
						Html::encode($model->track_number),
						['view', 'id' => $model->id],
						['title' => 'Просмотр посылки']
					);
				},
				'filterInputOptions' => [
					'class' => 'form-control',
					'placeholder' => '13 цифр',
					'maxlength' => 13,
					'pattern' => '\d{13}',
					'title' => 'Введите 13 цифр'
				],
			],
			[
				'attribute' => 'status',
				'format' => 'raw',
				'value' => function($model) {
					$statuses = Track::getStatusLabels();
					$status = $model->status;
					return $statuses[$status] ?? $status;
				},
				'filter' => Track::getStatusLabels(),
				'filterInputOptions' => [
					'class' => 'form-control',
					'prompt' => '-- Выберите статус --'
				],
			],
			[
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function($model) {
					return $model->created_at ?: null;
				},
				'filter' => false,
			],
			[
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function($model) {
					return $model->updated_at ?: null;
				},
				'filter' => false,
			],

        ],
		(!Yii::$app->user->isGuest) ? [
		    [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Track $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
		] : [])
    ]); ?>

</div>

<?php
// JS для маски ввода трек-номера в фильтре
$this->registerJs(<<<JS
    $('#tracksearch-track_number').on('input', function() {
        this.value = this.value.replace(/[^\d]/g, '').slice(0, 13);
    });
JS);
?>
