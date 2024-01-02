<?php

/**
 * @var $this \Untek\Lib\Web\View\Libs\View
 * @var $formView FormView|AbstractType[]
 * @var $formRender \Untek\Lib\Web\Form\Libs\FormRender
 * @var $dataProvider DataProvider
 * @var $baseUri string
 * @var $bundleCollection \Untek\Core\Collection\Interfaces\Enumerable | \Untek\Sandbox\Sandbox\Bundle\Domain\Entities\BundleEntity[]
 */

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Untek\Lib\Web\Html\Helpers\Url;
use Untek\Domain\DataProvider\Libs\DataProvider;
use Untek\Sandbox\Sandbox\Generator\Domain\Entities\ApiKeyEntity;

//dd($this->translate('core', 'action.send'));
?>

<div class="row">
    <div class="col-lg-12">
        <div class="list-group">
            <?php foreach ($bundleCollection as $bundleEntity): ?>
            <a href="<?= $this->url('generator/bundle/view', ['id' => $bundleEntity->getId()]) ?>" class="list-group-item list-group-item-action ">
                <?= $bundleEntity->getNamespace() ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
