<?php

namespace api;

use Exception;
use talismanfr\psbbank\api\Api;
use PHPUnit\Framework\TestCase;
use talismanfr\psbbank\api\vo\LoginRequest;
use talismanfr\psbbank\api\vo\OrderRequest;
use talismanfr\psbbank\shared\EmailValue;
use talismanfr\psbbank\shared\InnValue;
use talismanfr\psbbank\shared\PhoneValue;

class ApiTest extends TestCase
{
    /** @depends  testLoginRequestCreate
     * @depends  testApi
     */
    public function testLoginError(LoginRequest $loginRequest, Api $api)
    {
        $response = $api->login($loginRequest);
        $this->assertNotEmpty($response->getBody());
        $this->assertJson($response->getBody());
        $json = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('errors', $json);
    }

    /**
     * @group  correctLoginPassword
     * @return LoginRequest
     */
    public function testLoginFromEnv(): LoginRequest
    {
        $this->assertArrayHasKey('login', $_ENV);
        $this->assertArrayHasKey('password', $_ENV);
        return new LoginRequest($_ENV['login'], $_ENV['password']);
    }

    /**
     * @group correctLoginPassword
     * @depends testLoginFromEnv
     * @depends testApi
     * @param LoginRequest $loginRequest
     * @param Api $api
     * @return string
     * @throws Exception
     */
    public function testLoginSuccess(LoginRequest $loginRequest, Api $api): string
    {
        $response = $api->login($loginRequest);
        $this->assertNotEmpty($response->getBody());
        $this->assertJson($response->getBody());
        $json = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $json, 'data not found in json ' . $response->getBody());
        $this->assertArrayHasKey('access_token', $json['data'], 'token not found in json ' . $response->getBody());
        return $json['data']['access_token'];
    }

    public function testApi(): Api
    {

        $api = new Api($_ENV['url']);
        $this->assertInstanceOf(Api::class, $api);
        return $api;
    }

    public function testLoginRequestCreate(): LoginRequest
    {
        $login = new LoginRequest('test@test.ru', '123');
        $this->assertIsArray($login->toArray());
        return $login;
    }

    /**
     * @depends  testApi
     */
    public function testCitiesError(Api $api)
    {
        sleep(1);
        $response = $api->cities('123');
        $this->assertNotNull($response);
        $this->assertJson($response->getBody());
        $json = json_decode($response->getBody(), true);
        $this->assertEquals($response->getCode(), 401);
        $this->assertArrayHasKey('message', $json);
    }

    /**
     * @depends testLoginSuccess
     * @depends testApi
     * @group correctLoginPassword
     * @param string $token
     * @param Api $api
     * @throws Exception
     */
    public function testCitiesSuccess(string $token, Api $api)
    {
        sleep(1);
        $response = $api->cities($token);
        $this->assertNotNull($response);
        $this->assertJson($response->getBody());
        $json = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('data', $json, 'data not found in json ' . $response->getBody());
        $data = $json['data'];
        $this->assertNotEquals(count($data), 0);
        $this->assertArrayHasKey(0, $data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('city_id', $data[0]);
        $this->assertArrayHasKey('city_name', $data[0]);
        $this->assertArrayHasKey('city_timezone', $data[0]);
        $this->assertArrayHasKey('region', $data[0]);
    }

    /**
     * @depends testLoginSuccess
     * @depends testApi
     * @group correctLoginPassword
     * @param string $token
     * @param Api $api
     * @throws Exception
     */
    public function testOrderSuccess(string $token, Api $api)
    {
        sleep(1);
        $order = new OrderRequest(new InnValue('7743331870'), 'test firm', false, true,
            'tes test test', new PhoneValue('79675319123'), new EmailValue('test@test.ru'),
            $_ENV['cityId'], 'comment');

        $response = $api->createOrder($token, $order);
        $this->assertJson($response->getBody());
        echo $response->getBody();
    }

    /**
     * @depends testLoginSuccess
     * @depends testApi
     * @group  correctLoginPassword
     * @param string $token
     * @param Api $api
     * @return int
     * @throws Exception
     */
    public function testOrdersSuccess(string $token, Api $api): int
    {
        sleep(1);
        $response = $api->orders($token);
        $this->assertJson($response->getBody());
        $this->assertEquals($response->getCode(), 200);
        $json = json_decode($response->getBody(), true);
        $this->assertArrayHasKey('code', $json);
        $this->assertEquals($json['code'], 200);
        $this->assertArrayHasKey(0, $json['data'], $response->getBody());

        return $json['data'][0]['id'];
    }

    /**
     * @depends testLoginSuccess
     * @depends testApi
     * @depends testOrdersSuccess
     * @group correctLoginPassword
     * @param string $token
     * @param Api $api
     * @param int $idOrder
     * @throws Exception
     */
    public function testOrder(string $token, Api $api, int $idOrder)
    {
        sleep(1);
        $response = $api->order($token, $idOrder);
        $this->assertJson($response->getBody(), 'error response. code=' . $response->getCode() . PHP_EOL . $response->getBody());

    }
}
