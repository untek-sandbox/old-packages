<?php

namespace Untek\Sandbox\Sandbox\Deployer\Domain\Libs;

/**
 * Class PhpConfig
 * @package Untek\Sandbox\Sandbox\Deployer\Domain\Libs
 * @todo Как отсюда забрать результат?
 */
class PhpConfig
{

    private $config;

    public function __construct(string $config)
    {
        $this->config = $config;
    }

    public function set(string $name, $value)
    {
//        $this->config = preg_replace($this->generateExp($name), "$1$2$3$4$value$6$7", $this->config);
        $this->modify($name, "$1$2$3$4$value$6$7");
    }

    protected function modify(string $name, $expReplacement)
    {
        $this->config = preg_replace($this->generateExp($name), $expReplacement, $this->config);
    }

    public function get(string $name)
    {
        preg_match($this->generateExp($name), $this->config, $matches);
        if (isset($matches[5])) {
            return $matches[5];
        }
        return null;
    }

    public function isDisable(string $name): bool
    {
        preg_match($this->generateExp($name), $this->config, $matches);
        return trim($matches[1]) == ';';
    }

    public function disable(string $name): bool
    {
        if (!self::isDisable($name)) {
//            $this->config = preg_replace($this->generateExp($name), ";$2$3$4$5$6$7", $this->config);
            $this->modify($name, ";$2$3$4$5$6$7");
            return true;
        }
        return false;
    }

    public function enable(string $name): bool
    {
        if (!self::isDisable($name)) {
//            $this->config = preg_replace($this->generateExp($name), "$2$3$4$5$6$7", $this->config);
            $this->modify($name, "$2$3$4$5$6$7");
            return true;
        }
        return false;
    }

    protected function generateExp(string $name)
    {
        $quotedName = preg_quote($name);
        return '/(\s*;)?(\s*)(' . $quotedName . ')(\s*=\s*)(.+)(;.+)?(\n)/i';
    }
}
