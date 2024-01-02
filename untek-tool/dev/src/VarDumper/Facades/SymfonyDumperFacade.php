<?php

namespace Untek\Tool\Dev\VarDumper\Facades;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\CliContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\ServerDumper;
use Symfony\Component\VarDumper\VarDumper;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Core\Env\Helpers\EnvHelper;
use Untek\Tool\Dev\VarDumper\Dumper\JsonDumper;
use Untek\Tool\Dev\VarDumper\Dumper\TelegramDumper;

class SymfonyDumperFacade
{

    const URL = 'tcp://127.0.0.1:9912';

    private static $driver;

    public static function dumpInConsole(string $driver)
    {
        self::$driver = $driver;
        VarDumper::setHandler([self::class, 'handler']);
    }

    public static function handler($var)
    {
        $dumper = self::createServerDumper();
        $cloner = new VarCloner();
        $dumper->dump($cloner->cloneVar($var));
    }

    private static function createServerDumper(): DataDumperInterface
    {
        if (self::$driver == 'json') {
            return new JsonDumper(getenv('VAR_DUMPER_JSON_DIRECTORY'));
        } elseif (self::$driver == 'telegram') {
            return new TelegramDumper(getenv('VAR_DUMPER_BOT_TOKEN'), getenv('VAR_DUMPER_BOT_CHAT_ID'));
        } elseif (self::$driver == 'console') {
            $fallbackDumper = self::getDumper();
            $contextProviders = [
                'cli' => new CliContextProvider(),
                'source' => new SourceContextProvider(),
            ];
            return new ServerDumper(self::URL, $fallbackDumper, $contextProviders);
        } else {
            throw new InvalidConfigException('Unknown dumper driver "' . self::$driver . '"! See env config "VAR_DUMPER_OUTPUT".');
        }
    }

    private static function getDumper(): AbstractDumper
    {
        return EnvHelper::isConsole() ? new CliDumper() : new HtmlDumper();
    }
}
