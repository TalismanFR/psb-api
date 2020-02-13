<?php

namespace api\vo;

use talismanfr\psbbank\api\vo\LoginRequest;
use PHPUnit\Framework\TestCase;

class LoginRequestTest extends TestCase
{

    /**
     * @return LoginRequest
     */
    public function testCreate(): LoginRequest
    {
        $login = new LoginRequest('test@test.ru', '123');
        $this->assertInstanceOf(LoginRequest::class, $login);
        return $login;
    }

    /**
     * @depends testCreate
     * @param LoginRequest $loginReqeust
     * @return LoginRequest
     */
    public function testGetEmail(LoginRequest $loginReqeust): LoginRequest
    {
        $this->assertEquals('test@test.ru', $loginReqeust->getEmail());
        return $loginReqeust;
    }

    /**
     * @depends testGetEmail
     */
    public function testGetPassword(LoginRequest $loginRequest)
    {
        $this->assertEquals('123', $loginRequest->getPassword());

        return $loginRequest;
    }

    /**
     * @depends testGetPassword
     */
    public function testLoginRequestToArray(LoginRequest $loginRequest)
    {
        $ar = $loginRequest->toArray();

        $this->assertIsArray($ar);
        $this->assertEquals(['email' => 'test@test.ru', 'password' => '123'], $ar);
    }

}
