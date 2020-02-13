<?php


use talismanfr\psbbank\api\Api;
use talismanfr\psbbank\ApiService;
use PHPUnit\Framework\TestCase;
use talismanfr\psbbank\shared\CurlResponse;

class ApiServiceTest extends TestCase
{

    /** @var Api */
    private $api;

    public function setUp(): void
    {
        $this->api = $this->createStub(Api::class);

        $this->api
            ->method('login')
            ->will($this->onConsecutiveCalls(
                new CurlResponse(
                    200,
                    '',
                    '{"code":200,"data":{"id":5071,"fio":"Федор Федорович","agent":"ООО \"А Я ЕДА\"","access_token":"Zprsc2ac"}}'
                ),
                new CurlResponse(
                    200,
                    '',
                    '{"errors":{"code":400,"message":"Неправильный email или пароль."}}'
                ),
                new CurlResponse(
                    300,
                    '',
                    '{"errors":{"code":400,"message":"Неправильный email или пароль."}}'
                )
            ));
    }

    public function testLogin()
    {
        $service = new ApiService($this->api);

        $login = $service->login('test@test.ru', '123');

        $this->assertNotNull($login);
        $this->assertNull($login->getErrors());
        $this->assertEquals($login->getId(), 5071);
        $this->assertEquals($login->getToken(), 'Zprsc2ac');

        $login = $service->login('wrong@wrogn.ru', 'wrong');
        $this->assertNotNull($login);
        $this->assertNotNull($login->getErrors());
        $this->assertEquals($login->hasErrorCode(400), true);
        $this->assertTrue($login->isError());

        $login = $service->login('wrong@wrogn.ru', 'wrong');
        $this->assertNull($login);
    }
}
