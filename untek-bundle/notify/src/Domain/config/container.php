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
        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailRepositoryInterface' => EnvHelper::isTest() ? 'Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\File\EmailRepository' : 'Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\Telegram\EmailRepository',
        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\SmsRepositoryInterface' => EnvHelper::isTest() ? 'Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\File\SmsRepository' : 'Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\Telegram\SmsRepository',

        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrServiceInterface' => 'Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services\ToastrService',
        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\FlashServiceInterface' => 'Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services\FlashService',
        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\SmsServiceInterface' => 'Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services\SmsService',
        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\EmailServiceInterface' => 'Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Services\EmailService',
        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\FlashRepositoryInterface' => 'Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\Session\FlashRepository',
        //'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrRepositoryInterface' => 'Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\Session\ToastrRepository',
    ],
];