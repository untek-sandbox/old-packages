<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services;

use Untek\Domain\Service\Base\BaseService;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Enums\FlashMessageTypeEnum;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\FlashRepositoryInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\FlashServiceInterface;

class FlashService extends BaseService implements FlashServiceInterface
{

    const DEFAULT_DELAY = 5000;

    public function __construct(FlashRepositoryInterface $repository)
    {
        $this->setRepository($repository);
    }

    public function addSuccess(string $message, int $delay = self::DEFAULT_DELAY)
    {
        $this->add(FlashMessageTypeEnum::SUCCESS, $message, $delay);
    }

    public function addInfo(string $message, int $delay = self::DEFAULT_DELAY)
    {
        $this->add(FlashMessageTypeEnum::INFO, $message, $delay);
    }

    public function addWarning(string $message, int $delay = self::DEFAULT_DELAY)
    {
        $this->add(FlashMessageTypeEnum::WARNING, $message, $delay);
    }

    public function addError(string $message, int $delay = self::DEFAULT_DELAY)
    {
        $this->add(FlashMessageTypeEnum::ERROR, $message, $delay);
    }

    public function add(string $type, string $message, int $delay = self::DEFAULT_DELAY)
    {
        $this->getRepository()->add($type, $message, $delay);
    }
}
