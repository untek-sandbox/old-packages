<?php

namespace Untek\Lib\Web\Widget\Interfaces;

interface WidgetInterface2
{

    public static function widget(array $config = []): string;
    public function run(): string;

}