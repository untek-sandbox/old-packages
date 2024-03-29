<?php

namespace Untek\Lib\Web\RestApiApp\Test\Asserts;

use Symfony\Component\HttpFoundation\Response;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Framework\Rpc\Domain\Enums\RpcErrorCodeEnum;
use Untek\Lib\Components\Http\Enums\HttpStatusCodeEnum;
use Untek\Tool\Test\Asserts\BaseAssert;
use Untek\Tool\Test\Helpers\RestHelper;

class RestApiAssert extends BaseAssert
{

    protected $response;

    public function __construct(Response $response = null)
    {
        $this->response = $response;
    }
    
    public function assertPath($expected, string $path)
    {
        $responseBody = json_decode($this->response->getContent(), JSON_OBJECT_AS_ARRAY);
        $actual = ArrayHelper::getValue($responseBody, $path);
//        dd($responseBody);
        $this->assertEquals($expected, $actual);
        return $this;
    }

    public function assertData(array $data)
    {
        $responseBody = json_decode($this->response->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertEquals($data, $responseBody);
        return $this;
    }


    public function assertNotFound(string $message = null)
    {
        $this->assertError(HttpStatusCodeEnum::NOT_FOUND, $message);
        return $this;
    }

    public function assertForbidden(string $message = null)
    {
        $this->assertError(HttpStatusCodeEnum::FORBIDDEN, $message);
        return $this;
    }

    public function assertUnauthorized(string $message = null)
    {
        $this->assertError(HttpStatusCodeEnum::UNAUTHORIZED, $message);
        return $this;
    }

    public function assertError(int $code, string $message = null)
    {
        $this->assertErrorCode($code);
        if ($message) {
            $this->assertErrorMessage($message);
        }
        return $this;
    }

    public function assertErrorCode(int $code)
    {
//        $this->assertIsError();
        $this->assertEquals($code, $this->response->getError()['code']);
        return $this;
    }

    public function assertErrorMessage(string $message)
    {
//        $this->assertIsError();
        $this->assertEquals($message, $this->response->getError()['message']);
        return $this;
    }

    public function assertIsError(string $message = 'Response is not error')
    {
        $this->assertTrue($this->response->isError(), $message);
        return $this;
    }

    public function assertIsResult(string $message = 'Response is not success')
    {
        $this->assertTrue($this->response->isSuccess(), $message);
        return $this;
    }

    public function assertId($expected)
    {
        $this->assertEquals($expected, $this->response->getId());
        return $this;
    }

    public function assertResult($expectedResult)
    {
        $this->assertIsResult();
        if (is_array($expectedResult)) {
            $this->assertArraySubset($expectedResult, $this->response->getResult());
        } else {
            $this->assertEquals($expectedResult, $this->response->getResult());
        }
        return $this;
    }

    public function assertCollectionSize(int $expected)
    {
        $this->assertCount($expected, $this->response->getResult());
        $totalCount = $this->response->getMetaItem('totalCount', null);
        if ($totalCount !== null) {
            $this->assertEquals($expected, $totalCount);
        }
        return $this;
    }

    public function assertCollectionSizeByPath(int $expected, string $path)
    {
        $data = ArrayHelper::getValue($this->response->getResult(), $path);
        $this->assertCount($expected, $data);
        /*$totalCount = $this->response->getMetaItem('totalCount', null);
        if($totalCount !== null) {
            $this->assertEquals($expected, $totalCount);
        }*/
        return $this;
    }

    public function assertCollection($data)
    {
        $this->assertResult($data);
        $this->assertCollectionSize(count($data));
        return $this;
    }

    public function assertCollectionIsEmpty()
    {
        $this->assertIsResult();
        $this->assertCollectionSize(0);
        return $this;
    }

    public function assertCollectionItemsById(array $ids)
    {
        $this->assertIsResult();
        $this->assertCollectionSize(count($ids));

        $actualIds = ArrayHelper::getColumn($this->response->getResult(), 'id');
        sort($ids);
        sort($actualIds);
        $this->assertEquals($ids, $actualIds);
        return $this;
    }

    public function assertCollectionItemsByAttribute(array $values, string $attributeName)
    {
        $this->assertIsResult();
        $this->assertCollectionSize(count($values));

        $collection = $this->response->getResult();
        $this->assertItemsByAttribute($values, $attributeName, $collection);
        return $this;
    }

    private function assertCollectionCount(int $expected)
    {
        $this->assertIsResult();
        $this->assertCount($expected, $this->response->getResult());
        $this->assertEquals($expected, $this->response->getMetaItem('perPage'));
//        $this->assertEquals($expected, $this->response->getMetaItem('totalCount'));
    }

    public function assertPagination(int $totalCount = null, int $count, int $pageSize = null, int $page = 1)
    {
        if ($totalCount !== null) {
            $this->assertEquals($totalCount, $this->response->getMetaItem('totalCount'));
        }
//        if($count) {
        $this->assertCollectionCount($count);
//        }
        if ($pageSize !== null) {
            $this->assertEquals($pageSize, $this->response->getMetaItem('perPage'));
        }

        $this->assertEquals($page, $this->response->getMetaItem('page'));
    }

    public function assertUnprocessableEntity(array $fieldNames = [])
    {
        $this->assertIsError();
        $this->assertErrorMessage('Parameter validation error');
        $this->assertErrorCode(RpcErrorCodeEnum::SERVER_ERROR_INVALID_PARAMS);
        if ($fieldNames) {
            foreach ($this->response->getError()['data'] as $item) {
                if (empty($item['field']) || empty($item['message'])) {
                    $this->expectExceptionMessage('Invalid errors array!');
                }
                $expectedBody[] = $item['field'];
            }
            $this->assertEquals($fieldNames, $expectedBody);
        }
        return $this;
    }

    public function assertUnprocessableEntityErrors(array $errors)
    {
        $this->assertIsError();
        $this->assertErrorMessage('Parameter validation error');
        $this->assertErrorCode(RpcErrorCodeEnum::SERVER_ERROR_INVALID_PARAMS);
        $this->assertEquals(
            $this->unprocessableEntityErrorsToFlat($errors),
            $this->unprocessableEntityErrorsToFlat($this->response->getError()['data'])
        );
        return $this;
    }

    protected function unprocessableEntityErrorsToFlat(array $errors)
    {
        $flat = [];
        foreach ($errors as $error) {
            $flat[] = "{$error['field']}|{$error['message']}";
        }
        sort($flat);
        return $flat;
    }
}
