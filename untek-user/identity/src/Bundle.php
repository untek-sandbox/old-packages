<?php

namespace Untek\User\Identity;

use Untek\Core\Bundle\Base\BaseBundle;

class Bundle extends BaseBundle
{

    public function getName(): string
    {
        return 'userIdentity';
    }

    public function deps(): array
    {
        return [
//            new \Untek\Bundle\User\Bundle(['all']),
            new \Untek\User\Authentication\Bundle(['all']),
            new \Untek\User\Confirm\Bundle(['all']),
        ];
    }

    public function symfonyRpc(): array
    {
        return [
            __DIR__ . '/Rpc/config/identity-routes.php',
        ];
    }

    public function migration(): array
    {
        return [
            __DIR__ . '/Domain/Migrations',
        ];
    }

    public function rbac(): array
    {
        return [
            __DIR__ . '/Domain/config/rbac.php',
        ];
    }

    public function container(): array
    {
        return [
            __DIR__ . '/Domain/config/container.php',
//            __DIR__ . '/Domain/config/container-script.php',
        ];
    }

    public function entityManager(): array
    {
        return [
            __DIR__ . '/Domain/config/em.php',
        ];
    }
}
