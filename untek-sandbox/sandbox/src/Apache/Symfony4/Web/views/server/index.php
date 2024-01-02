<?php

/**
 * @var $this \Untek\Lib\Web\View\Libs\View
 * @var $formView FormView|AbstractType[]
 * @var $dataProvider DataProvider
 * @var $collection \Untek\Core\Collection\Interfaces\Enumerable | \Untek\Sandbox\Sandbox\Apache\Domain\Entities\ServerEntity[]
 * @var $baseUri string
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Untek\Lib\Web\Html\Helpers\Url;
use Untek\Lib\I18Next\Facades\I18Next;
use Untek\Domain\DataProvider\Libs\DataProvider;
use Untek\Lib\Web\TwBootstrap\Widgets\Collection\CollectionWidget;
use Untek\Lib\Web\TwBootstrap\Widgets\Format\Formatters\ActionFormatter;
use Untek\Lib\Web\TwBootstrap\Widgets\Format\Formatters\LinkFormatter;

$attributes = [
    [
        'label' => 'ServerName',
        'attributeName' => 'ServerName',
        'format' => 'html',
        'value' => function (\Untek\Sandbox\Sandbox\Apache\Domain\Entities\ServerEntity $serverEntity) {
            return \Untek\Lib\Web\Html\Helpers\Html::a($serverEntity->getServerName(), 'http://' . $serverEntity->getServerName(), ['target' => '_blank']);
        },
    ],
    [
        'label' => 'DocumentRoot',
        'attributeName' => 'DocumentRoot',
    ],
    [
        'formatter' => [
            'class' => ActionFormatter::class,
            'actions' => [
                'view',
                'update',
                'delete',
            ],
            'baseUrl' => $baseUri,
        ],
    ],
];

?>

<div class="row">
    <div class="col-lg-12">
        <?= CollectionWidget::widget([
            'collection' => $collection,
            'attributes' => $attributes,
        ]) ?>
        <div class="float-left111">
            <a class="btn btn-primary" href="<?= Url::to([$baseUri . '/create']) ?>" role="button">
                <i class="fa fa-plus"></i>
                <?= I18Next::t('core', 'action.create') ?>
            </a>
        </div>
    </div>
</div>
