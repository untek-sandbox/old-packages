<?php

namespace Untek\Sandbox\Sandbox\Person\Symfony4\Web\Controllers;

use Untek\Sandbox\Sandbox\Person\Domain\Enums\Rbac\AppPersonPermissionEnum;
use Untek\Sandbox\Sandbox\Person\Domain\Interfaces\Services\PersonServiceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Untek\Bundle\Eav\Domain\Interfaces\Services\EntityServiceInterface;
use Untek\Bundle\Notify\Domain\Interfaces\Services\ToastrServiceInterface;
use Untek\Lib\Web\Html\Helpers\Url;
use Untek\Domain\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Lib\Web\Controller\Base\BaseWebController;
use Untek\Lib\Web\Controller\Interfaces\ControllerAccessInterface;
use Untek\Lib\Web\Form\Traits\ControllerFormTrait;
use Untek\Lib\Web\TwBootstrap\Widgets\Breadcrumb\BreadcrumbWidget;

class PersonController extends BaseWebController implements ControllerAccessInterface
{

    use ControllerFormTrait;

    protected $viewsDir = __DIR__ . '/../views/person';
    protected $toastrService;
    protected $service;
    protected $entityService;
    protected $breadcrumbWidget;

    public function __construct(
        ToastrServiceInterface $toastrService,
        FormFactoryInterface $formFactory,
        CsrfTokenManagerInterface $tokenManager,
        EntityServiceInterface $entityService,
        BreadcrumbWidget $breadcrumbWidget,
        PersonServiceInterface $personService
    )
    {
        $this->entityService = $entityService;
        $this->toastrService = $toastrService;
        $this->service = $personService;
        $this->setFormFactory($formFactory);
        $this->setTokenManager($tokenManager);

        $this->breadcrumbWidget = $breadcrumbWidget;
        $title = 'Person settings';
        $this->breadcrumbWidget->add($title, Url::to(['/person-settings']));
        $this->getView()->addAttribute('title', $title);
    }

    public function access(): array
    {
        return [
            'update' => [
                AppPersonPermissionEnum::PERSON_INFO_UPDATE,
            ],
        ];
    }

    public function update(Request $request): Response
    {
        $entityCollection = $this->entityService->allByCategoryId(1);
        $defaultName = $entityCollection->first()->getName();
        $name = $request->query->get('entity', $defaultName);
        $personEntity = $this->service->findOneByAuth($name);
        $form = $this->service->createForm($name, $personEntity->toArray());
        $buildForm = $this->buildForm($form, $request);
        if ($buildForm->isSubmitted() /*&& $buildForm->isValid()*/) {
            try {
                $this->service->updateMyData($name, $form);
                $this->toastrService->success(['core', 'message.saved_success']);
            } catch (UnprocessibleEntityException $e) {
                $this->setUnprocessableErrorsToForm($buildForm, $e);
            }
        }

        return $this->render('update', [
            'formView' => $buildForm->createView(),
            'entityCollection' => $entityCollection,
        ]);
    }
}
