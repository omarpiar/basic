<?php

use yii\helpers\Html;
use app\models\Usuarios;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UsuariosSearch $model */
/** @var app\models\Usuarios $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="usuarios-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'strNombre') ?>

    <?= $form->field($model, 'strPassword') ?>

    <?= $form->field($model, 'EstadoUsuario')->dropDownList(
        $model::ESTADOS,
        ['prompt' => 'Todos los estados']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
