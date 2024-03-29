<?php

namespace Untek\Lib\Web\Html\Helpers;

use Untek\Lib\Web\TwBootstrap\Widgets\Table\TableWidget;

class TableHelper
{

    public static function render(array $body, array $headers = [], string $tableClass = 'table table-bordered table-condensed table-sm')
    {
        return TableWidget::widget([
            'tableClass' => $tableClass,
            'body' => $body,
            'header' => $headers,
        ]);
    }
}
