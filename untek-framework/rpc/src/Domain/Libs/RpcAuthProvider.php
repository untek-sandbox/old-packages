<?php

namespace Untek\Framework\Rpc\Domain\Libs;

use Untek\Framework\Rpc\Domain\Entities\RpcRequestEntity;
use Untek\Framework\Rpc\Domain\Entities\RpcResponseEntity;

class RpcAuthProvider
{

    protected $rpcProvider;
    protected $authMethod = 'authentication.getTokenByPassword';

    public function __construct(RpcProvider $rpcProvider)
    {
        $this->rpcProvider = $rpcProvider;
    }

    /*public function getAuthMethod(): string
    {
        return $this->authMethod;
    }

    public function setAuthMethod(string $authMethod): void
    {
        $this->authMethod = $authMethod;
    }*/
    
    public function authBy(string $login, string $password): ?string
    {
        $response = $this->authRequest($login, $password);
        $token = $response->getResult()['token'] ?? null;
        return $token;
    }

    public function authRequest(string $login, string $password): RpcResponseEntity
    {
        $request = new RpcRequestEntity();
        $request->setMethod($this->authMethod);
        $request->setParams([
            'login' => $login,
            'password' => $password,
        ]);
        $response = $this->rpcProvider->sendRequestByEntity($request);

        /*$response = $this->rpcProvider->sendRequest('authentication.getTokenByPassword', [
            'login' => $login,
            'password' => $password,
        ]);*/
        return $response;
    }
}
