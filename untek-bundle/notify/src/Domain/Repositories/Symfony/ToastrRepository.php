<?php

namespace Untek\Component\Web\Widget\Widgets\Toastr\Infrastructure\Drivers\Symfony;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Component\Web\Widget\Widgets\Toastr\Domain\Model\ToastrEntity;
use Untek\Component\Web\Widget\Widgets\Toastr\Application\Services\ToastrRepositoryInterface;
use Untek\Domain\Validator\Helpers\ValidationHelper;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Core\Collection\Libs\Collection;
use Untek\Domain\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Domain\EntityManager\Traits\EntityManagerAwareTrait;

class ToastrRepository implements ToastrRepositoryInterface
{

    use EntityManagerAwareTrait;

    private static $all = [];
    private $session;

    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->setEntityManager($em);
        $this->session = $session;
    }

    public function create(ToastrEntity $toastrEntity)
    {
        ValidationHelper::validateEntity($toastrEntity);
        self::$all[] = $toastrEntity;
        $this->sync();
    }

    public function findAll(): Enumerable
    {
        $items = $this->session->get('flash-alert', []);
        return new Collection($items);
    }

    public function clear()
    {
        self::$all[] = [];
        $this->session->remove('flash-alert');
    }

    private function sync()
    {
        $this->session->set('flash-alert', self::$all);
    }
}
