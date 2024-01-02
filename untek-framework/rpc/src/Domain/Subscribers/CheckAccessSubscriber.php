<?php

namespace Untek\Framework\Rpc\Domain\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Untek\Domain\EntityManager\Traits\EntityManagerAwareTrait;
use Untek\Framework\Rpc\Domain\Enums\RpcEventEnum;
use Untek\Framework\Rpc\Domain\Events\RpcRequestEvent;
use Untek\User\Authentication\Domain\Traits\GetUserTrait;

class CheckAccessSubscriber implements EventSubscriberInterface
{

    use EntityManagerAwareTrait;
    use GetUserTrait;

    public function __construct(
        private AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            RpcEventEnum::BEFORE_RUN_ACTION => 'onBeforeRunAction',
        ];
    }

    public function onBeforeRunAction(RpcRequestEvent $event)
    {
        $requestEntity = $event->getRequestEntity();
        $methodEntity = $event->getMethodEntity();
        if ($methodEntity->getPermissionName()) {
            $this->checkAccess($methodEntity->getPermissionName());
        }
    }

    /**
     * Проверка прав доступа
     * @param string $permissionName
     * @throws AccessDeniedException
     */
    private function checkAccess(string $permissionName)
    {
        $isGranted = $this->authorizationChecker->isGranted($permissionName);
        if (!$isGranted) {
            throw new AccessDeniedException();
        }
    }
}
