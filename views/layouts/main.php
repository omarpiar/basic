<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
\yii\bootstrap5\BootstrapAsset::register($this);
\yii\bootstrap5\BootstrapPluginAsset::register($this);

// Si quieres íconos de Bootstrap Icons:
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css');

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
]);

// Preparamos los items del menú
$menuItems = [
    ['label' => 'Cartelera', 'url' => ['/site/index']],
    //['label' => 'Mail', 'url' => ['/test-mail/test']],
    //['label' => 'Contact', 'url' => ['/site/contact']],
];

// Items para usuarios autenticados
if (!Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'CRUD Peliculas', 'url' => ['/peliculas/index']];
}

// Item de Login/Logout
$menuItems[] = Yii::$app->user->isGuest
    ? ['label' => 'Login', 'url' => ['/site/login']]
    : [
        'label' => Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Bienvenido ' . Html::encode(Yii::$app->user->identity->strNombre),
                ['class' => 'nav-link btn btn-link logout border-0 p-0']
            )
            . Html::endForm(),
        'encode' => false,
        'options' => ['class' => 'nav-item d-flex align-items-center']
    ];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-auto'],
    'items' => array_filter($menuItems) // Filtramos cualquier valor null
]);

NavBar::end();
?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
    
</main>

<footer id="footer" class="mt-auto py-5 bg-dark text-white">
    <div class="container">
        <div class="row">
            <!-- Columna 1: Información del cine -->
            <div class="col-lg-4 mb-4">
                <h5 class="text-uppercase mb-4">Cine Yii</h5>
                <p class="mb-1"><i class="bi bi-geo-alt-fill me-2"></i> Av. Principal 123, Tula de Allende</p>
                <p class="mb-1"><i class="bi bi-telephone-fill me-2"></i> Tel: (773) 160-0021</p>
                <p class="mb-1"><i class="bi bi-envelope-fill me-2"></i> cineYii@cineyii.com</p>
                <p class="mb-0"><i class="bi bi-clock-fill me-2"></i> Horario: 10:00 AM - 11:00 PM</p>
            </div>

            <!-- Columna 2: Enlaces rápidos -->
            <div class="col-lg-4 mb-4">
                <h5 class="text-uppercase mb-4">Enlaces Rápidos</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <?= Html::a('Cartelera', ['/site/index'], ['class' => 'text-white text-decoration-none']) ?>
                    </li>
                    <li class="mb-2">
                        <?= Html::a('Próximos Estrenos', ['/site/index'], ['class' => 'text-white text-decoration-none']) ?>
                    </li>
                    <li class="mb-2">
                        <?= Html::a('Promociones', ['/site/index'], ['class' => 'text-white text-decoration-none']) ?>
                    </li>
                    <li class="mb-2">
                        <?= Html::a('Términos y Condiciones', ['/site/about'], ['class' => 'text-white text-decoration-none']) ?>
                    </li>
                </ul>
            </div>

            <!-- Columna 3: Redes sociales y acceso -->
            <div class="col-lg-4 mb-4">
                <h5 class="text-uppercase mb-4">Síguenos</h5>
                <div class="mb-4">
                    <a href="#" class="text-white me-3 fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3 fs-5"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white me-3 fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white me-3 fs-5"><i class="bi bi-tiktok"></i></a>
                </div>
                
                <h5 class="text-uppercase mb-3">Acceso</h5>
                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('Iniciar Sesión', ['/site/login'], [
                        'class' => 'btn btn-outline-light w-100 mb-2'
                    ]) ?>
                    <p class="small">¿No tienes cuenta? <?= Html::a('Regístrate', ['/usuarios/create'], [
                        'class' => 'text-white fw-bold'
                    ]) ?></p>
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="me-2">Bienvenido <?= Html::encode(Yii::$app->user->identity->strNombre) ?></span>
                        <?= Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline']) ?>
                            <?= Html::submitButton('Cerrar Sesión', [
                                'class' => 'btn btn-sm btn-outline-light'
                            ]) ?>
                        <?= Html::endForm() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <hr class="my-4 bg-secondary">

        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="small mb-0">&copy; <?= date('Y') ?> Cine Yii. Todos los derechos reservados.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="small mb-0">
                    <?= Yii::powered() ?> | 
                    <?= Html::a('Admin', ['/site/admin'], ['class' => 'text-white text-decoration-none']) ?>
                </p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
