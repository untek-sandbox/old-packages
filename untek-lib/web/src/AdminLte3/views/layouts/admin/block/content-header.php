<?php

/**
 * @var array $menu
 * @var View $this
 * @var string $content
 */

use Untek\Core\Container\Libs\Container;
use Untek\Lib\Web\View\Libs\View;
use Untek\Lib\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;

?>

<div class="col-sm-6">
    <h1 class="m-0 text-dark">
        <?= $this->getAttribute('title', '') ?>
    </h1>
</div>

<div class="col-sm-6">
    <?php
    /** @var BreadcrumbWidget $breadcrumbWidget */
    $breadcrumbWidget = \Untek\Core\Container\Helpers\ContainerHelper::getContainer()->get(BreadcrumbWidget::class);
    $breadcrumbWidget->wrapTemplate = '<ol class="breadcrumb float-sm-right">{items}</ol>';
    if (count($breadcrumbWidget->items) > 1) {
        echo $breadcrumbWidget->render();
    }
    ?>
</div>
