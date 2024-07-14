<?php

return [
    'singletons' => [
        'Untek\Bundle\Notify\Application\Services\ToastrRepositoryInterface' => 'Untek\Bundle\Notify\Infrastructure\Drivers\Symfony\ToastrRepository',
        'Untek\\Bundle\\Language\\Domain\\Interfaces\\Repositories\\SwitchRepositoryInterface' => 'Untek\\Bundle\\Language\\Domain\\Repositories\\Symfony4\\SwitchRepository',
        'Untek\\Bundle\\Language\\Domain\\Interfaces\\Repositories\\StorageRepositoryInterface' => 'Untek\\Bundle\\Language\\Domain\\Repositories\\Symfony4\\StorageRepository',
    ],
];
