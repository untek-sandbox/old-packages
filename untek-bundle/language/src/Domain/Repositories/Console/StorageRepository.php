<?php

namespace Untek\Bundle\Language\Domain\Repositories\Console;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Bundle\Language\Domain\Interfaces\Repositories\StorageRepositoryInterface;

class StorageRepository implements StorageRepositoryInterface
{

    public function setLanguage(string $locale)
    {

    }

    public function getLanguage(): string
    {
        return 'ru';
    }
}
