<?php

namespace Untek\Database\Doctrine\Domain\Facades;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Untek\Core\DotEnv\Domain\Libs\DotEnvMap;
use Untek\Database\Base\Domain\Helpers\ConfigHelper;

class DoctrineFacade
{

    public static function createConnection(): Connection
    {
        if (getenv('DATABASE_URL')) {
            $dbconfig = ConfigHelper::parseDsn(getenv('DATABASE_URL'));
        } else {
            $dbconfig = DotEnvMap::get('db');
        }
        $connectionConfig = [
            'dbname' => $dbconfig['database'] ?? $dbconfig['dbname'],
            'user' => $dbconfig['username'],
            'password' => $dbconfig['password'],
            'host' => $dbconfig['host'] ?? '127.0.0.1',
            'driver' => 'pdo_' . $dbconfig['driver'] ?? 'mysql',
            'charset' => 'utf8',
            'driverOptions' => [
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ],
        ];
        $config = new Configuration;
        $connection = DriverManager::getConnection($connectionConfig, $config);
        return $connection;
    }
}