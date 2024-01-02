<?php

namespace Untek\Framework\Rpc\Symfony4\Libs;

use Throwable;
use Untek\Framework\Rpc\Domain\Entities\RpcRequestCollection;
use Untek\Framework\Rpc\Domain\Entities\RpcRequestEntity;
use Untek\Framework\Rpc\Domain\Entities\RpcResponseCollection;
use Untek\Framework\Rpc\Domain\Enums\HttpHeaderEnum;
use Untek\Framework\Rpc\Domain\Enums\RpcBatchModeEnum;
use Untek\Framework\Rpc\Domain\Enums\RpcErrorCodeEnum;
use Untek\Framework\Rpc\Domain\Helpers\ErrorHelper;
use Untek\Framework\Rpc\Domain\Helpers\RequestHelper;
use Untek\Framework\Rpc\Domain\Interfaces\Services\ProcedureServiceInterface;
use Untek\Framework\Rpc\Domain\Libs\ResponseFormatter;
use Untek\Framework\Rpc\Domain\Libs\RpcJsonResponse;

class RpcRequestHandler
{

    protected $procedureService;
    protected $responseFormatter;
    protected $rpcJsonResponse;

    public function __construct(
        ResponseFormatter $responseFormatter,
        RpcJsonResponse $rpcJsonResponse,
        ProcedureServiceInterface $procedureService
    )
    {
        $this->responseFormatter = $responseFormatter;
        $this->rpcJsonResponse = $rpcJsonResponse;
        $this->procedureService = $procedureService;
    }

    public function handleJsonData(string $jsonData)
    {
        $requestData = json_decode($jsonData, true);
        $jsonErrorCode = json_last_error();
        if ($jsonErrorCode) {
            $message = $this->jsonErrorCodeToMessage($jsonErrorCode);
            $responseCollection = $this->createErrorResponseByMessage($message, RpcErrorCodeEnum::PARSE_ERROR_INVALID_JSON);
            $batchMode = RpcBatchModeEnum::SINGLE;
        } elseif (empty($requestData)) {
            $message = "Invalid request. Empty request!";
            $responseCollection = $this->createErrorResponseByMessage($message);
            $batchMode = RpcBatchModeEnum::SINGLE;
        } else {
            //$this->logger->info('request', $requestData ?: []);
//            $controllerEvent = new ControllerEvent($controllerInstance, $actionName, $request);
//            $this->getEventDispatcher()->dispatch($controllerEvent, ControllerEventEnum::BEFORE_ACTION);

            $isBatchRequest = RequestHelper::isBatchRequest($requestData);
            $batchMode = $isBatchRequest ? RpcBatchModeEnum::BATCH : RpcBatchModeEnum::SINGLE;
            $requestCollection = RequestHelper::createRequestCollection($requestData);
            $responseCollection = $this->handleData($requestCollection);
        }
        $responseData = $this->rpcJsonResponse->encode($responseCollection, $batchMode);
        return $responseData;
    }

    private function handleData(RpcRequestCollection $requestCollection): RpcResponseCollection
    {
        $responseCollection = new RpcResponseCollection();
        foreach ($requestCollection->getCollection() as $requestEntity) {
            /** @var RpcRequestEntity $requestEntity */
//            $requestEntity->addMeta(HttpHeaderEnum::IP, $_SERVER['REMOTE_ADDR']);
            try {
                ob_start();
                $responseEntity = $this->procedureService->callOneProcedure($requestEntity);
                ob_clean();
                $responseCollection->add($responseEntity);
            } catch (Throwable $e) {
                $responseEntity = $this->responseFormatter->forgeErrorResponse(RpcErrorCodeEnum::APPLICATION_ERROR, $e->getMessage());
                $responseCollection->add($responseEntity);
            }
        }
        return $responseCollection;
    }

    private function jsonErrorCodeToMessage(int $jsonErrorCode): string
    {
        $errorDescription = ErrorHelper::descriptionFromJsonErrorCode($jsonErrorCode);
        $message = "Invalid request. Parse JSON error! {$errorDescription}";
        return $message;
    }

    private function createErrorResponseByMessage(string $message, $code = RpcErrorCodeEnum::SERVER_ERROR_INVALID_REQUEST): RpcResponseCollection
    {
        $responseEntity = $this->responseFormatter->forgeErrorResponse($code, $message);
        $responseCollection = new RpcResponseCollection();
        $responseCollection->add($responseEntity);
        return $responseCollection;
    }
}
