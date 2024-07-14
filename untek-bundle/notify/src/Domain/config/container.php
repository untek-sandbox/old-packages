<?php

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\NullTransport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Untek\Core\Env\Helpers\EnvHelper;

return [
    'singletons' => [
//        MailerInterface::class => Mailer::class,
//        TransportInterface::class => NullTransport::class,
        'Untek\Bundle\Notify\Application\Services\EmailRepositoryInterface' => EnvHelper::isTest() ? 'Untek\Bundle\Notify\Domain\Repositories\File\EmailRepository' : 'Untek\Bundle\Notify\Domain\Repositories\Telegram\EmailRepository',
        'Untek\Bundle\Notify\Application\Services\SmsRepositoryInterface' => EnvHelper::isTest() ? 'Untek\Bundle\Notify\Domain\Repositories\File\SmsRepository' : 'Untek\Bundle\Notify\Domain\Repositories\Telegram\SmsRepository',

        'Untek\Bundle\Notify\Application\Services\ToastrServiceInterface' => 'Untek\Bundle\Notify\Infrastructure\Services\ToastrService',
        'Untek\Bundle\Notify\Application\Services\FlashServiceInterface' => 'Untek\Bundle\Notify\Infrastructure\Services\FlashService',
        'Untek\Bundle\Notify\Application\Services\SmsServiceInterface' => 'Untek\Bundle\Notify\Infrastructure\Services\SmsService',
        'Untek\Bundle\Notify\Application\Services\EmailServiceInterface' => 'Untek\Bundle\Notify\Infrastructure\Services\EmailService',
        'Untek\Bundle\Notify\Application\Services\FlashRepositoryInterface' => 'Untek\Bundle\Notify\Domain\Repositories\Session\FlashRepository',
        //'Untek\Bundle\Notify\Application\Services\ToastrRepositoryInterface' => 'Untek\Bundle\Notify\Domain\Repositories\Session\ToastrRepository',
    ],
];