<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Usuarios $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="usuarios-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'strNombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'strPassword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'EstadoUsuario')->dropDownList(
        $model::ESTADOS,
        ['prompt' => 'Seleccione un estado...']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
