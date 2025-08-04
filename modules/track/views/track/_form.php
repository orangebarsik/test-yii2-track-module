<?php

use app\modules\track\models\Track;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\track\models\Track $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="track-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'track_number')->textInput([
        'maxlength' => 13,
        'pattern' => '\d{13}',
        'title' => 'Трек-номер должен содержать ровно 13 цифр',
		'readonly' => !$model->isNewRecord,
		'style' => !$model->isNewRecord ? 'background-color: #f8f9fa;' : ''
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(
        Track::getStatusLabels(),
        ['prompt' => '-- Выберите статус --']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Подключаем jQuery маску для поля трек-номера
$this->registerJs(<<<JS
    $('#track-track_number').on('input', function() {
        this.value = this.value.replace(/[^\d]/g, '').slice(0, 13);
    });
JS);
?>
