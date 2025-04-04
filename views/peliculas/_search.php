<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PeliculasSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="peliculas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'strNombre') ?>

    <?= $form->field($model, 'strSinopsis') ?>

    <?= $form->field($model, 'strHorario') ?>

    <?= $form->field($model, 'strGenero')->dropDownList(
        Peliculas::GENEROS,
        ['prompt' => 'Todos los géneros']
    ) ?>

    <?= $form->field($model, 'strSala')->dropDownList(
        Peliculas::SALAS,
        ['prompt' => 'Todas las salas']
    ) ?>

    <?= $form->field($model, 'strEstadoPelicula')->dropDownList(
        Peliculas::ESTADOS,
        ['prompt' => 'Todos los estados']
    ) ?>

    <?php // echo $form->field($model, 'Imagen') ?>

    <?php // echo $form->field($model, 'strUrlVideo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
