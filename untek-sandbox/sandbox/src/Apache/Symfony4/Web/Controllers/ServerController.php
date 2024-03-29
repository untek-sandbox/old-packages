<?php

namespace Untek\Sandbox\Sandbox\Apache\Symfony4\Web\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Untek\Lib\Web\Controller\Base\BaseWebController;
use Untek\Lib\Web\Controller\Base\BaseWebCrudController;
use Untek\Sandbox\Sandbox\Apache\Domain\Services\ServerService;

class ServerController extends BaseWebCrudController
{

    protected $viewsDir = __DIR__ . '/../views/server';
    protected $baseUri = '/apache/server';
    private $serverService;

    public function __construct(
        ServerService $serverService
    )
    {
        $this->serverService = $serverService;
    }

    public function index(Request $request): Response
    {
        $collection = $this->serverService->findAll();
        return $this->render('index', [
            'collection' => $collection,
            'baseUri' => $this->baseUri,
        ]);
    }

    public function view(Request $request): Response
    {
        dd($request);
        $name = $request->query->get('id');
        $entity = $this->serverService->findOneByName($name);
        return $this->render('view', [
            'entity' => $entity,
        ]);
    }
}
