<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\track\models\Track;

/** @var yii\web\View $this */
/** @var app\modules\track\models\Track $model */

$this->title = $model->track_number;
$this->params['breadcrumbs'][] = ['label' => 'Посылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="track-view">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту посылку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	<?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'track_number',
            [
				'attribute' => 'status',
				'format' => 'raw',
				'value' => function($model) {
					$statuses = Track::getStatusLabels();
					$status = $model->status;
					return $statuses[$status] ?? $status;
				}
			],
			[
				'attribute' => 'created_at',
				'format' => 'datetime',
				'value' => function($model) {
					return $model->created_at ?: null;
				}
			],
			[
				'attribute' => 'updated_at',
				'format' => 'datetime',
				'value' => function($model) {
					return $model->updated_at ?: null;
				}
			],
        ],
    ]) ?>

</div>
