<?php

use app\models\Peliculas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PeliculasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Peliculas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="peliculas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Peliculas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'strNombre',
            [
                'attribute' => 'strGenero',
                'filter' => Peliculas::GENEROS, // Aquí usamos los estados definidos en el modelo
                'content' => function($model) {
                    return $model->strGenero;
                }
            ],
            'strSinopsis:ntext',
            'strHorario',
            [
                'attribute' => 'strSala',
                'filter' => Peliculas::GENEROS, // Aquí usamos los estados definidos en el modelo
                'content' => function($model) {
                    return $model->strSala;
                }
            ],
            [
                'attribute' => 'strEstadoPelicula',
                'filter' => Peliculas::GENEROS, // Aquí usamos los estados definidos en el modelo
                'content' => function($model) {
                    return $model->strEstadoPelicula;
                }
            ],
            //'strSala',
            
            //'Imagen',
            //'strUrlVideo',
            //'strEstadoPelicula',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Peliculas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
