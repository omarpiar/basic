<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Peliculas $model */

$this->title = 'Create Peliculas';
$this->params['breadcrumbs'][] = ['label' => 'Peliculas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peliculas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
