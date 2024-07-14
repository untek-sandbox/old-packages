<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Domain\Repositories\Telegram;

use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Entities\SmsEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\SmsRepositoryInterface;
use Untek\Framework\Telegram\Domain\Facades\Bot;

class SmsRepository implements SmsRepositoryInterface
{

    public function send(SmsEntity $smsEntity)
    {
        $message =
            '# SMS' . PHP_EOL .
            'Phone: ' . $smsEntity->getPhone() . PHP_EOL .
            'Message: ' . $smsEntity->getMessage();
        Bot::sendMessage($message);
    }
}
