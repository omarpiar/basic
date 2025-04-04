<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Peliculas $model */
/** @var yii\widgets\ActiveForm $form */
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<div class="peliculas-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'strNombre')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'strGenero')->dropDownList(
        $model::GENEROS,
        ['prompt' => 'Seleccione un género...']
    ) ?>
    
    <?= $form->field($model, 'strSinopsis')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'strHorario')->textInput([
        'class' => 'form-control datetimepicker',
        'placeholder' => 'Seleccione el horario'
    ]) ?>
    
    <?= $form->field($model, 'strSala')->dropDownList(
        $model::SALAS,
        ['prompt' => 'Seleccione una sala...']
    ) ?>
    
    <?= $form->field($model, 'strEstadoPelicula')->dropDownList(
        $model::ESTADOS,
        ['prompt' => 'Seleccione un estado...']
    ) ?>
    
    <?= $form->field($model, 'imagenFile')->fileInput() ?>
<?php if (!$model->isNewRecord && $model->Imagen): ?>
    <div class="form-group">
        <label>Imagen Actual:</label><br>
        <img src="<?= $model->Imagen ?>" style="max-width: 200px;">
    </div>
<?php endif; ?>
    
    <?= $form->field($model, 'strUrlVideo')->textInput(['maxlength' => true]) ?>
    <?php if (!$model->isNewRecord && $model->strUrlVideo): ?>
        <div class="form-group">
            <label>Vista Previa Video:</label><br>
            <div class="embed-responsive embed-responsive-16by9" style="max-width: 400px;">
                <iframe class="embed-responsive-item" src="<?= $model->strUrlVideo ?>" allowfullscreen></iframe>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
// Configuración del datetimepicker
$this->registerJs('
    $(document).ready(function(){
        $(".datetimepicker").datetimepicker({
            format: "H:i",       // Formato de 24 horas
            datepicker: false,   // No mostrar calendario
            step: 30,            // Intervalo de 30 minutos
            minTime: "08:00",    // Hora mínima (opcional)
            maxTime: "23:30",    // Hora máxima (opcional)
            defaultTime: "12:00",// Hora por defecto (opcional)
            scrollInput: false   // Mejor usabilidad en móviles
        });
    });
');
?>