<?php

namespace Untek\Bundle\Messenger\Domain\Interfaces\Services;

use Untek\Domain\Service\Interfaces\CrudServiceInterface;
use Untek\Bundle\Messenger\Domain\Entities\BotEntity;

interface BotServiceInterface extends CrudServiceInterface
{

    public function authByToken(string $botToken): BotEntity;
}
