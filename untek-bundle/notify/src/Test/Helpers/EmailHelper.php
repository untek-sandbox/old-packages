<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Test\Helpers;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\EmailEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailRepositoryInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\File\EmailRepository;
use Untek\Core\Container\Helpers\ContainerHelper;

class EmailHelper
{

    const REGEXP_DIGIT = '/([\d+]{6})/';

    public static function getLastActivationCode() {
        $messageEntity = self::oneLast();
        return self::parse($messageEntity->getBody(), self::REGEXP_DIGIT);
    }

    public static function oneLast(): EmailEntity {
        /** @var EmailRepositoryInterface $emailRepo */
        $emailRepo = ContainerHelper::getContainer()->get(EmailRepository::class);
        return $emailRepo->oneLast();
    }

    private static function parse(string $message, string $regExp): string {
        preg_match($regExp, $message, $matches);
        return $matches[0];
    }
}
