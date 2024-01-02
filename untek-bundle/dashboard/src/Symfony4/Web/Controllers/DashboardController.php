<?php

namespace Untek\Bundle\Dashboard\Symfony4\Web\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Untek\Bundle\Dashboard\Domain\Enums\Rbac\DashboardPermissionEnum;
use Untek\Bundle\Dashboard\Domain\Interfaces\Services\DashboardServiceInterface;
use Untek\Lib\Web\Controller\Base\BaseWebController;
use Untek\Lib\Web\Controller\Interfaces\ControllerAccessInterface;
use Untek\Lib\Web\Form\Traits\ControllerFormTrait;

class DashboardController extends BaseWebController implements ControllerAccessInterface
{

    use ControllerFormTrait;

    protected $viewsDir = __DIR__ . '/../views/dashboard';
    protected $service;

    public function __construct(
        DashboardServiceInterface $service
    )
    {
        $this->service = $service;
    }

    public function access(): array
    {
        return [
            'index' => [
                DashboardPermissionEnum::ALL,
            ],
        ];
    }

    public function index(Request $request): Response
    {
        return $this->render('index', [
            'widgetConfigList' => $this->service->findAll(),
        ]);
    }
}
