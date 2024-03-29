<?php

namespace Untek\Bundle\Messenger\Domain\Enums\Rbac;

use Untek\Core\Enum\Interfaces\GetLabelsInterface;
use Untek\Core\Contract\Rbac\Interfaces\GetRbacInheritanceInterface;
use Untek\Core\Contract\Rbac\Traits\CrudRbacInheritanceTrait;
use Untek\User\Rbac\Domain\Enums\Rbac\SystemRoleEnum;

class MessengerMessagePermissionEnum implements GetLabelsInterface, GetRbacInheritanceInterface
{

    use CrudRbacInheritanceTrait;

    const CRUD = 'oMessengerMessageCrud';
    const ALL = 'oMessengerMessageAll';
    const ONE = 'oMessengerMessageOne';
    const CREATE = 'oMessengerMessageCreate';
    const UPDATE = 'oMessengerMessageUpdate';
    const DELETE = 'oMessengerMessageDelete';
    const RESTORE = 'oMessengerMessageRestore';

    public static function getLabels()
    {
        return [
            self::CRUD => 'Мессенджер. Сообщения. Полный доступ',
            self::ALL => 'Мессенджер. Сообщения. Просмотр списка',
            self::ONE => 'Мессенджер. Сообщения. Просмотр записи',
            self::CREATE => 'Мессенджер. Сообщения. Создание',
            self::UPDATE => 'Мессенджер. Сообщения. Редактирование',
            self::DELETE => 'Мессенджер. Сообщения. Удаление',
            self::RESTORE => 'Мессенджер. Сообщения. Восстановление',
        ];
    }

    public static function getInheritance()
    {
        return [
            self::CRUD => [
                self::ALL,
                self::ONE,
                self::CREATE,
                self::UPDATE,
                self::DELETE,
//                self::RESTORE,
            ],
            SystemRoleEnum::GUEST => [
                self::CREATE,
            ]
        ];
    }
}
