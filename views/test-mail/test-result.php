<?php
use yii\helpers\Html;

$this->title = 'Prueba de Correo';
?>
<div class="test-mail-test-result">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <strong>Detalles del error:</strong>
            <pre><?= Html::encode($error) ?></pre>
        </div>
    <?php endif; ?>

    <p>
        <?= Html::a('Volver a intentar', ['test'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Ir al inicio', ['site/index'], ['class' => 'btn btn-default']) ?>
    </p>
</div>