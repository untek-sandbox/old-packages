<?php

return [
    'singletons' => [
        'Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrRepositoryInterface' => 'Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Drivers\Symfony\ToastrRepository',
        'Untek\\Bundle\\Language\\Domain\\Interfaces\\Repositories\\SwitchRepositoryInterface' => 'Untek\\Bundle\\Language\\Domain\\Repositories\\Symfony4\\SwitchRepository',
        'Untek\\Bundle\\Language\\Domain\\Interfaces\\Repositories\\StorageRepositoryInterface' => 'Untek\\Bundle\\Language\\Domain\\Repositories\\Symfony4\\StorageRepository',
    ],
];
