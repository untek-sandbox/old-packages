<?php

namespace Untek\Domain\Validator\Tests\Unit;

use Untek\Domain\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Domain\Entity\Helpers\EntityHelper;
use Untek\Domain\Validator\Helpers\ValidationHelper;
use Untek\Domain\Validator\Tests\Libs\AccessEntity;
use Untek\Tool\Test\Base\BaseTest;

include __DIR__ . '/../Libs/AccessEntity.php';

final class EntityValidateTest extends BaseTest
{

    public function testSuccess()
    {
        $entity = new AccessEntity;
        $entity->setProjectId(1);
        $entity->setUserId(2);

        ValidationHelper::validateEntity($entity);
        $this->assertTrue(true);
    }

    public function testRequired()
    {
        $entity = new AccessEntity;

        //$this->expectException(UnprocessibleEntityException::class);

        $expected = [
            [
                'field' => 'userId',
                //'message' => 'This value should not be blank.',
            ],
            [
                'field' => 'projectId',
                //'message' => 'This value should not be blank.',
            ],
        ];
        try {
            ValidationHelper::validateEntity($entity);
        } catch (UnprocessibleEntityException $e) {
            $this->assertUnprocessibleEntityException($expected, $e);
        }
    }

    public function testInvalidType()
    {
        $entity = new AccessEntity;
        $entity->setProjectId('qwer');
        $entity->setUserId(2);

        $expected = [
            [
                "field" => "projectId",
//                "message" => "Значение должно быть положительным.",
            ],
        ];
        try {
            ValidationHelper::validateEntity($entity);
        } catch (UnprocessibleEntityException $e) {
            $this->assertUnprocessibleEntityException($expected, $e);
        }
    }

    public function testInvalidRange()
    {
        $entity = new AccessEntity;
        $entity->setProjectId(-3);
        $entity->setUserId(2);

        $expected = [
            [
                'field' => 'projectId',
//                'message' => 'Значение должно быть положительным.',
            ],
        ];
        try {
            ValidationHelper::validateEntity($entity);
        } catch (UnprocessibleEntityException $e) {
            $this->assertUnprocessibleEntityException($expected, $e);
        }
    }

}