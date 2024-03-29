<?php

use Untek\Bundle\Storage\Rpc\Controllers\MyFileController;
use Untek\Bundle\Storage\Domain\Enums\Rbac\StorageMyFilePermissionEnum;

return [
    [
        'method_name' => 'storageMyFile.all',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => StorageMyFilePermissionEnum::ALL,
        'handler_class' => MyFileController::class,
        'handler_method' => 'all',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'storageMyFile.oneById',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => StorageMyFilePermissionEnum::ONE,
        'handler_class' => MyFileController::class,
        'handler_method' => 'oneById',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'storageMyFile.create',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => StorageMyFilePermissionEnum::CREATE,
        'handler_class' => MyFileController::class,
        'handler_method' => 'add',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'storageMyFile.update',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => StorageMyFilePermissionEnum::UPDATE,
        'handler_class' => MyFileController::class,
        'handler_method' => 'update',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
    [
        'method_name' => 'storageMyFile.delete',
        'version' => '1',
        'is_verify_eds' => false,
        'is_verify_auth' => true,
        'permission_name' => StorageMyFilePermissionEnum::DELETE,
        'handler_class' => MyFileController::class,
        'handler_method' => 'delete',
        'status_id' => 100,
        'title' => null,
        'description' => null,
    ],
];