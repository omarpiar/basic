<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Peliculas;
use app\models\PeliculasSearch;
use yii\helpers\Url;

// Registrar los assets (si no están ya registrados)
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js', [
    'depends' => [\yii\web\JqueryAsset::className()]
]);

/** @var yii\web\View $this */
$this->title = 'Cartelera de Cine';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="peliculas-cartelera container py-4">

<h1 class="text-center mb-5 display-4 fw-bold"><?= Html::encode($this->title) ?></h1>

<!-- Filtro de búsqueda - Bootstrap 5 -->
<div class="card shadow-sm mb-5">
    <div class="card-body p-4">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['cartelera'],
            'options' => ['class' => 'row g-3 align-items-end']
        ]); ?>

            <div class="col-md-4">
                <?= $form->field($searchModel, 'strNombre', [
                    'inputOptions' => [
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Buscar por título'
                    ]
                ])->label('Título de la película') ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($searchModel, 'strGenero')->dropDownList(
                    Peliculas::GENEROS,
                    [
                        'prompt' => 'Todos los géneros',
                        'class' => 'form-select form-select-lg'
                    ]
                )->label('Género') ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($searchModel, 'strHorario')->textInput([
                'class' => 'form-control timepicker-filter',
                'placeholder' => 'Todos los horarios',
                'autocomplete' => 'off'
                ])->label('Horario') ?>
            </div>

            <div class="col-md-2 d-grid">
                <?= Html::submitButton('Filtrar', ['class' => 'btn btn-primary btn-lg']) ?>
                <?= Html::a('Limpiar', ['cartelera'], ['class' => 'btn btn-outline-secondary btn-lg mt-2']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- Tarjetas de películas - Bootstrap 5 Cards -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($dataProvider->getModels() as $pelicula): ?>
    <div class="col">
        <div class="card h-100 shadow border-0">
            <!-- Imagen con efecto hover -->
            <div class="card-img-top overflow-hidden" style="height: 400px;">
                <?= Html::img(
                    $pelicula->Imagen ? Url::to($pelicula->Imagen) : Url::to('@web/images/default-movie.jpg'),
                    [
                        'class' => 'img-fluid w-100 h-100 object-fit-cover transition-scale',
                        'alt' => $pelicula->strNombre,
                        'style' => 'transition: transform 0.3s ease;'
                    ]
                ) ?>
            </div>
            
            <div class="card-body">
                <h2 class="card-title h4 fw-bold"><?= Html::encode($pelicula->strNombre) ?></h2>
                
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary rounded-pill"><?= Html::encode($pelicula->strGenero) ?></span>
                    <span class="badge bg-secondary rounded-pill">Sala: <?= Html::encode($pelicula->strSala) ?></span>
                    <span class="badge bg-success rounded-pill"><?= Html::encode($pelicula->strHorario) ?></span>
                </div>
                
                <p class="card-text text-muted"><?= Html::encode(mb_strimwidth($pelicula->strSinopsis, 0, 120, '...')) ?></p>
            </div>
            
            <div class="card-footer bg-transparent border-top-0">
                <!-- Botón para ver trailer - Modal BS5 -->
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#trailerModal<?= $pelicula->id ?>">
                    <i class="bi bi-play-circle me-2"></i>Ver Trailer
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para el trailer - Bootstrap 5 -->
    <div class="modal fade" id="trailerModal<?= $pelicula->id ?>" tabindex="-1" aria-labelledby="trailerModalLabel<?= $pelicula->id ?>" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h3 class="modal-title fs-5" id="trailerModalLabel<?= $pelicula->id ?>"><?= Html::encode($pelicula->strNombre) ?></h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 ratio ratio-16x9">
                    <?php if ($pelicula->strUrlVideo): ?>
                        <?php if (strpos($pelicula->strUrlVideo, 'youtube') !== false): ?>
                            <!-- Si es un video de YouTube -->
                            <?= Html::tag('iframe', '', [
                                'src' => preg_replace(
                                    "/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
                                    "//www.youtube.com/embed/$1",
                                    $pelicula->strUrlVideo
                                ),
                                'frameborder' => '0',
                                'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
                                'allowfullscreen' => '',
                                'class' => 'w-100 h-100'
                            ]) ?>
                        <?php else: ?>
                            <!-- Para otros videos -->
                            <video controls class="w-100 h-100">
                                <source src="<?= Html::encode($pelicula->strUrlVideo) ?>" type="video/mp4">
                                Tu navegador no soporta videos HTML5.
                            </video>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <p class="text-center">No hay trailer disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Paginación - Bootstrap 5 -->
<div class="row mt-5">
    <div class="col-12">
        <?= \yii\bootstrap5\LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'pagination justify-content-center'],
            'listOptions' => ['class' => 'pagination pagination-lg'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link disabled']
        ]) ?>
    </div>
</div>
</div>

<!-- Estilos personalizados -->
<style>
.transition-scale:hover {
    transform: scale(1.05);
}

.object-fit-cover {
    object-fit: cover;
}

.card {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>

<?php
// Script para inicializar el timepicker en el filtro
$this->registerJs('
    $(document).ready(function(){
        $(".timepicker-filter").datetimepicker({
            format: "H:i",
            datepicker: false,
            step: 30,
            minTime: "08:00",    // Hora mínima (opcional)
            maxTime: "23:30",    // Hora máxima (opcional)
            defaultTime: "12:00",// Hora por defecto (opcional)
            scrollInput: false,
            onChangeDateTime: function(dp, $input){
                // Forzar el submit del formulario al seleccionar un horario
                $input.closest("form").submit();
            }
        });
        
        // Opcional: Limpiar el filtro
        $(".timepicker-filter").after(
            \'<button type="button" class="btn btn-outline-secondary btn-clear-time" style="position: absolute; right: 0; top: 0; height: 100%; border: none; background: transparent;">\'+
            \'<i class="bi bi-x-circle"></i>\'+
            \'</button>\'
        );
        
        $(".btn-clear-time").click(function(){
            $(this).siblings(".timepicker-filter").val("");
            $(this).closest("form").submit();
        });
    });
');
?>
