<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Application\Services;

interface FlashServiceInterface
{

    public function addSuccess(string $message);

    public function addInfo(string $message);

    public function addWarning(string $message);

    public function addError(string $message);

    public function add(string $type, string $message);
}
