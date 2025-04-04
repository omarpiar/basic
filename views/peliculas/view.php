<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Peliculas $model */

$this->title = $model->strNombre; // Cambiado para mostrar el nombre en lugar del ID
$this->params['breadcrumbs'][] = ['label' => 'Películas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="peliculas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Estás seguro de que quieres eliminar esta película?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'strNombre',
                    'strGenero',
                    [
                        'attribute' => 'strSinopsis',
                        'format' => 'ntext',
                        'contentOptions' => ['style' => 'white-space: normal;']
                    ],
                    'strHorario',
                    'strSala',
                    'strEstadoPelicula',
                    [
                        'attribute' => 'strUrlVideo',
                        'format' => 'raw',
                        'value' => function($model) {
                            if ($model->strUrlVideo) {
                                // Convertir URL de YouTube a embed si es necesario
                                $videoUrl = $model->strUrlVideo;
                                if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                                    $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                }
                                return Html::a('Ver video', $videoUrl, ['target' => '_blank', 'class' => 'btn btn-info']);
                            }
                            return 'No disponible';
                        }
                    ],
                ],
            ]) ?>
        </div>
        
        <div class="col-md-4">
            <?php if ($model->Imagen): ?>
                <div class="text-center">
                    <h3>Imagen de la película</h3>
                    <img src="<?= Html::encode($model->Imagen) ?>" 
                         class="img-responsive img-thumbnail" 
                         style="max-height: 300px; margin-bottom: 20px;"
                         alt="Poster de <?= Html::encode($model->strNombre) ?>">
                </div>
            <?php endif; ?>
            
            <?php if ($model->strUrlVideo): ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <?php 
                    $videoUrl = $model->strUrlVideo;
                    if (strpos($videoUrl, 'youtube.com/watch?v=') !== false) {
                        $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                    }
                    ?>
                    <iframe class="embed-responsive-item" 
                            src="<?= Html::encode($videoUrl) ?>" 
                            allowfullscreen
                            style="margin-top: 20px;"></iframe>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>