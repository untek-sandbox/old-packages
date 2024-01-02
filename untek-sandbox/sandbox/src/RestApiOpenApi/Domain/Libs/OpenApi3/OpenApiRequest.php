<?php

namespace Untek\Sandbox\Sandbox\RestApiOpenApi\Domain\Libs\OpenApi3;

use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\FileSystem\Helpers\FileStorageHelper;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Domain\Entity\Helpers\EntityHelper;
use Untek\Framework\Rpc\Domain\Entities\RpcRequestEntity;
use Untek\Framework\Rpc\Domain\Entities\RpcResponseEntity;
use Untek\Lib\Components\Store\Drivers\Yaml;
use Untek\Sandbox\Sandbox\RestApiOpenApi\Domain\Dto\RequestDto;
use Untek\Sandbox\Sandbox\RpcClient\Symfony4\Admin\Forms\RequestForm;
use Untek\Sandbox\Sandbox\RpcMock\Domain\Libs\HasherHelper;

class OpenApiRequest
{

    private $sourceDirectory;

    public function __construct(string $sourceDirectory)
    {
        $this->sourceDirectory = $sourceDirectory;
    }

    protected function generateRpcRequest(RpcRequestEntity $rpcRequestEntity, RpcResponseEntity $rpcResponseEntity)
    {
        $data = $this->getData($rpcRequestEntity);
        $data = $this->forgePaginate($rpcResponseEntity, $data);
        $data = $this->clearPayloadTails($data);
        $requestArray = [
            "jsonrpc" => "2.0",
            "method" => $rpcRequestEntity->getMethod(),
            "params" => $data,
        ];
        return $requestArray;
    }

    protected function clearPayloadTails($data)
    {
        if (empty($data['body'])) {
            unset($data['body']);
        }

        if (empty($data['meta'])) {
            unset($data['meta']);
        }
        return $data;
    }

    protected function generateRpcResponse(RpcRequestEntity $rpcRequestEntity, RpcResponseEntity $rpcResponseEntity)
    {
        $rpcResponse = [
            "jsonrpc" => "2.0",
        ];
        if ($rpcResponseEntity->getError()) {
            $rpcResponse['error'] = $rpcResponseEntity->getError();
        }
        if ($rpcResponseEntity->getResult()) {
            $rpcResponse['result']['body'] = $rpcResponseEntity->getResult();
        }
        $responseMeta = $rpcResponseEntity->getMeta();
        if ($responseMeta) {
            $rpcResponse['result']['meta'] = $responseMeta;
        }
        if (!empty($rpcResponse['result'])) {
            $rpcResponse['result'] = $this->clearPayloadTails($rpcResponse['result']);
        }
        return $rpcResponse;
    }

    protected function getData(RpcRequestEntity $rpcRequestEntity)
    {
        $data = [
            'body' => [],
            'meta' => [],
        ];
        if ($rpcRequestEntity->getParams()) {
            $data['body'] = $rpcRequestEntity->getParams();
        }
        if ($rpcRequestEntity->getMeta()) {
            $meta = $rpcRequestEntity->getMeta();
            if (array_key_exists('timestamp', $meta)) {
                unset($meta['timestamp']);
            }
            if (array_key_exists('version', $meta)) {
                unset($meta['version']);
            }
            if (array_key_exists('Authorization', $meta)) {
                unset($meta['Authorization']);
            }
            $data['meta'] = $meta;
        }
        return $data;
    }

    protected function isHasAuth(RpcRequestEntity $rpcRequestEntity): bool
    {
        if ($rpcRequestEntity->getMeta() == null) {
            return false;
        }
        return array_key_exists('Authorization', $rpcRequestEntity->getMeta());
    }

    protected function forgePaginate(RpcResponseEntity $rpcResponseEntity, $data)
    {
        $responseMeta = $rpcResponseEntity->getMeta();
        $isPaginate = isset($responseMeta['perPage']) && isset($responseMeta['totalCount']) && isset($responseMeta['page']);
        if ($isPaginate) {
            $data['body']['perPage'] = $responseMeta['perPage'];
            $data['body']['page'] = $responseMeta['page'];
        }
        return $data;
    }

    public function createPostRequest(RequestDto $requestDto) {// Request $request, Response $response, RequestForm $requestForm
        $dataSchemaEncoder = new DataSchema();

//        $rpcRequest = $this->generateRpcRequest($request, $response);

//        dd($requestDto->body);

        $responseSchema = $dataSchemaEncoder->encode($requestDto->response->body);
        $responseSchema['example'] = $requestDto->response->body;
//        $rpcResponse = $this->generateRpcResponse($request, $response);

//        $methodName = $request->getMethod();
//        list($tag, $actionName) = explode('.', $methodName);

//        $tag = 'common';
        $tag = trim($requestDto->uri, '/');

        $actionName = $requestDto->uri;

        $postConfig = [
            'tags' => [
                $tag
            ],
            'summary' => 'Description',
            'responses' => [
                200 => [
                    'content' => [
                        'application/json' => [
                            'schema' => $responseSchema
                        ]
                    ]
                ]
            ],
        ];


        if($requestDto->body) {
            $requestSchema = $dataSchemaEncoder->encode($requestDto->body);
            $requestSchema['example'] = $requestDto->body;
            $postConfig['requestBody'] = [
                'content' => [
                    'application/json' => [
                        'schema' => $requestSchema
                    ]
                ]
            ];
        }


        if($requestDto->query) {
            $postConfig['parameters'] = $dataSchemaEncoder->encodeParameters($requestDto->query);
        }

        /*$responseMeta = $response->getMeta();
        if ($responseMeta) {
            $postConfig['responses'][200]['headers'] = $dataSchemaEncoder->encode($responseMeta)['properties'];
        }

        if ($this->isHasAuth($request)) {
            $postConfig['security'][] = [
                'bearerAuth' => []
            ];
        }*/

        return $postConfig;
    }
}