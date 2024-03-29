<?php

/**
 * @var View $this
 * @var IdentityEntityInterface $identity
 * @var string $userMenuHtml
 */

use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Lib\Web\Html\Helpers\Html;
use Untek\Lib\I18Next\Facades\I18Next;
use Untek\Lib\Web\View\Libs\View;

?>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
       aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user"></i>
        <?= $identity->getUsername() ?>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
        <?= $userMenuHtml ?>
        <a class="dropdown-item" href="#" onclick="$('#logout-form').submit()">
            <i class="fas fa-sign-out-alt"></i>
            <?= I18Next::t('authentication', 'auth.logout_title') ?>
        </a>
    </div>
</li>

<?= Html::beginForm(['/logout'], 'post', ['id' => 'logout-form']) . Html::endForm(); ?>
