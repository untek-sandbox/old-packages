<?php

namespace Untek\Lib\Web\TwBootstrap\Widgets\Format\Formatters;

use Untek\Lib\I18Next\Facades\I18Next;

class NullFormatter extends BaseFormatter implements FormatterInterface
{

    public function render($items)
    {
        return '<i class="text-muted">' . I18Next::t('core', 'main.empty') . '</i>';
    }
}
