<?php

namespace Untek\Sandbox\Sandbox\Kafka\Messenger\Stamp;

use longlang\phpkafka\Consumer\ConsumeMessage;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class ConsumeMessageStamp implements StampInterface
{
    private ConsumeMessage $consumeMessage;

    public function __construct(ConsumeMessage $consumeMessage)
    {
        $this->consumeMessage = $consumeMessage;
    }

    public function getConsumeMessage(): ConsumeMessage
    {
        return $this->consumeMessage;
    }
}
