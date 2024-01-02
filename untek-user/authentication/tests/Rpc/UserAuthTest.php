<?php

namespace Untek\User\Authentication\Tests\Rpc\User;

use Untek\Framework\Rpc\Domain\Enums\RpcErrorCodeEnum;
use Untek\Framework\Rpc\Test\BaseRpcTest;

class UserAuthTest extends BaseRpcTest
{

    protected function fixtures(): array
    {
        return [
            'rpc_route',
            'user_credential',
            'user_token',
            'rbac_assignment',
            'rbac_inheritance',
            'settings_system',
            'summary_attempt',
            'notify_type',
            'notify_type_i18n',
            'notify_type_transport',
            'notify_transport',
        ];
    }

    protected function defaultRpcMethod(): ?string
    {
        return 'authentication.getTokenByPassword';
    }

    // проверка на приход токена
    public function testGetTokenSuccess()
    {
        $request = $this->createRequest();
        $request->setParams(
            [
                'login' => "admin",
                'password' => "Wwwqqq111",
            ]
        );

        $response = $this->sendRequestByEntity($request);
        $result = $response->getResult();
        $token = $result['token'];

        $this->assertStringContainsString('bearer', $token);
    }

    public function testFailAttempt()
    {
        if (getenv('MY_PROJECT_DIRECTORY') == null) {
            $this->markTestSkipped();
        }
        $request = $this->createRequest();
        $request->setParams(
            [
                'login' => "admin",
                'password' => 'qwerty123#$%',
            ]
        );

        for ($i = 0; $i < 2; $i++) {
            $response = $this->sendRequestByEntity($request);
            $this->getRpcAssert($response)->assertErrorCode(RpcErrorCodeEnum::SERVER_ERROR_INVALID_PARAMS);
        }

        $response = $this->sendRequestByEntity($request);
        $this->getRpcAssert($response)
            ->assertErrorCode(RpcErrorCodeEnum::APPLICATION_ERROR)
            ->assertErrorMessage('Попытки исчерпаны. Действие заблокировано.');

        $response = $this->sendRequestByEntity($request);
        $this->getRpcAssert($response)
            ->assertErrorCode(RpcErrorCodeEnum::APPLICATION_ERROR)
            ->assertErrorMessage('Попытки исчерпаны. Повторите позже.');
    }

    public function testSuccessAttempt()
    {
        $request = $this->createRequest();
        $request->setParams(
            [
                'login' => "admin",
                'password' => "Wwwqqq111",
            ]
        );

        for ($i = 0; $i < 5; $i++) {
            $response = $this->sendRequestByEntity($request);
            $result = $response->getResult();
            $token = $result['token'];
            $this->assertStringContainsString('bearer', $token);
        }
    }

    public function testGetTokenBadPassword()
    {
        $request = $this->createRequest();
        $request->setParamItem('login', "admin");
        $request->setParamItem('password', 'badPassword');
        $response = $this->sendRequestByEntity($request);

        $expected = [
            [
                "field" => "password",
                "message" => "Неверный пароль",
            ],
        ];

        $this->getRpcAssert($response)->assertUnprocessableEntityErrors($expected);
    }

    public function testGetTokenNotFoundLogin()
    {
        $request = $this->createRequest();
        $request->setParamItem('login', 'qwerty123456');
        $request->setParamItem('password', 'badPassword');
        $response = $this->sendRequestByEntity($request);

        $expected = [
            [
                "field" => "login",
                "message" => "Пользователь не существует",
            ],
        ];

        $this->getRpcAssert($response)->assertUnprocessableEntityErrors($expected);
    }

    public function testGetTokenEmptyPassword()
    {
        $request = $this->createRequest();
        $request->setParamItem('login', "admin");
        $response = $this->sendRequestByEntity($request);

        $expected = [
            [
                "field" => "password",
                "message" => "Значение не должно быть пустым.",
            ],
        ];

        $this->getRpcAssert($response)->assertUnprocessableEntityErrors($expected);
    }
}
