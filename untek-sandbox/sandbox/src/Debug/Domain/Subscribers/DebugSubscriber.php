<?php

namespace Untek\Sandbox\Sandbox\Debug\Domain\Subscribers;

use Untek\Sandbox\Sandbox\Debug\Domain\Entities\RequestEntity;
use Untek\Sandbox\Sandbox\Debug\Domain\Libs\Profiler;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\EventDispatcher\Event;
use Untek\Core\App\Interfaces\AppInterface;
use Untek\Core\Container\Traits\ContainerAwareTrait;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Domain\Entity\Interfaces\EntityIdInterface;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Core\App\Enums\AppEventEnum;

/**
 * Отладка и профилирование.
 *
 * Конфигурация в dotEnv по имени "DEBUG_PROFILING_LOG".
 * Значения: 0/1
 */
class DebugSubscriber implements EventSubscriberInterface
{

    use ContainerAwareTrait;

    private $app;

    public function __construct(ContainerInterface $container = null, AppInterface $app)
    {
        $this->setContainer($container);
        $this->app = $app;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AppEventEnum::BEFORE_INIT_ENV => 'onAnyEvent',
            AppEventEnum::AFTER_INIT_ENV => 'onAnyEvent',
            AppEventEnum::BEFORE_INIT_CONTAINER => 'onAnyEvent',
            AppEventEnum::AFTER_INIT_CONTAINER => 'onAnyEvent',
            AppEventEnum::BEFORE_INIT_BUNDLES => 'onAnyEvent',
            AppEventEnum::AFTER_INIT_BUNDLES => 'onAnyEvent',
            AppEventEnum::BEFORE_INIT_DISPATCHER => 'onAnyEvent',
            AppEventEnum::AFTER_INIT_DISPATCHER => 'onAnyEvent',

            KernelEvents::REQUEST => 'onAnyEvent',
            KernelEvents::EXCEPTION => 'onAnyEvent',
            KernelEvents::RESPONSE => 'onAnyEvent',
            KernelEvents::FINISH_REQUEST => 'onAnyEvent',
            KernelEvents::TERMINATE => [
                ['onAnyEvent', 0],
                ['onTerminate', -10],
            ],
        ];
    }

    public function onAnyEvent(Event $event, string $eventName)
    {
        Profiler::add($eventName);
        /*if ($eventName === KernelEvents::TERMINATE) {
            $this->persistLog();
        }*/
    }

    public function onTerminate(Event $event, string $eventName)
    {
        $this->persistLog();
    }

    protected static function getUri(): string
    {
        $request = Request::createFromGlobals();
        return trim($request->getRequestUri(), '/');
    }

    protected function persist(EntityIdInterface $entity): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get(EntityManagerInterface::class);
        $em->persist($entity);
    }

    protected function persistLog()
    {
        if (getenv('DEBUG_PROFILING_LOG')) {
            return;
        }

        $profilingCollection = Profiler::all();
        $lastTimestamp = $profilingCollection->last()->getTimestamp();
        $totalRuntime = round($lastTimestamp - $_SERVER['MICRO_TIME'], 4);

        $requestEntity = new RequestEntity();
        $requestEntity->setUuid(Uuid::v4()->toRfc4122());
        $requestEntity->setAppName($this->app->appName());
        $requestEntity->setUrl(self::getUri());
        $requestEntity->setRuntime($totalRuntime);
        $this->persist($requestEntity);

        foreach ($profilingCollection as $profilingEntity) {
            $profilingEntity->setRequestId($requestEntity->getId());
            $this->persist($profilingEntity);
        }
    }
}
