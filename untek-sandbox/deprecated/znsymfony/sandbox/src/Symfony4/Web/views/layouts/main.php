<?php

/**
 * @var array $menu
 * @var View $this
 * @var string $content
 */

use Untek\Symfony\Sandbox\Symfony4\Web\Helpers\ModuleHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Lib\Web\WebApp\Assets\AppAsset;
use Untek\Lib\Web\Layout\Widgets\Script\ScriptWidget;
use Untek\Lib\Web\Layout\Widgets\Style\StyleWidget;
use Untek\Lib\Web\View\Libs\View;
use Untek\Lib\Web\Widget\Widgets\Toastr\ToastrWidget;

$moduleId = ModuleHelper::getCurrentModule();

(new AppAsset())->register($this);

$menu = ModuleHelper::map('App\\' . Inflector::camelize($moduleId));

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title ?? '' ?></title>
    <?= StyleWidget::widget(['view' => $this]) ?>
<!--    <link rel="stylesheet" href="/node_modules/code-prettify/loader/prettify.css" />-->
    <!--<link rel="stylesheet" href="/node_modules/code-prettify/loader/skins/desert.css" />-->
</head>
<body>

<?= $this->renderFile(__DIR__ . '/blocks/navbar.php') ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-<?= !empty($menu) ? '9' : '12' ?>">
            <?= $this->renderFile(__DIR__ . '/blocks/breadcrumbs.php') ?>
            <?= $content ?>
        </div>
        <?php if(!empty($menu)): ?>
            <div class="col-3">
                <?= $this->renderFile(
                    __DIR__ . '/blocks/menu.php', [
                    'moduleId' => $moduleId,
                    'menu' => $menu,
                ]) ?>
            </div>
        <?php endif ?>
    </div>
</div>

<hr/>

<?= ToastrWidget::widget(['view' => $this]) ?>
<?= StyleWidget::widget(['view' => $this]) ?>
<?= ScriptWidget::widget(['view' => $this]) ?>

<div>
    <small class="text-muted">
        <?= round(microtime(true) - $_SERVER['MICRO_TIME'], 5) ?>
    </small>
</div>

</body>
</html>
