<?php

namespace Untek\Lib\Web\TwBootstrap\Widgets\Format\Formatters;

class StringFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($value)
    {
        return htmlspecialchars($value);
    }
}
