<?php


use talismanfr\psbbank\api\Api;
use talismanfr\psbbank\api\vo\OrderRequest;
use talismanfr\psbbank\ApiService;
use PHPUnit\Framework\TestCase;
use talismanfr\psbbank\shared\CurlResponse;
use talismanfr\psbbank\shared\EmailValue;
use talismanfr\psbbank\shared\InnValue;
use talismanfr\psbbank\shared\PhoneValue;
use talismanfr\psbbank\vo\Order;

class ApiServiceCreateOrderTest extends TestCase
{

    /** @var Api */
    private $api;

    public function setUp(): void
    {
        $this->api = $this->createStub(Api::class);

        $this->api
            ->method('createOrder')
            ->will($this->onConsecutiveCalls(
                new CurlResponse(
                    401,
                    '',
                    '{"code":200,"warning":null,"data":{"id":155775}}'
                ),
                new CurlResponse(
                    200,
                    '',
                    '{"errors":{"code":400,"message":"inn: В системе найден дубликат."}}'
                ),
                new CurlResponse(
                    503,
                    '',
                    '{"errors":{"code":400,"message":"inn: В системе найден дубликат."}}'
                )
            ));
    }

    public function testCreateOrder()
    {
        $orderReq = new OrderRequest(new InnValue('9724004969'), 'test firm', false, true,
            'tes test test', new PhoneValue('79675319122'), new EmailValue('test@test.ru'),
            $_ENV['cityId'], 'comment');
        $service = new ApiService($this->api);

        $order = $service->createOrder('token', $orderReq);
        $this->assertNotNull($order);
        $this->assertFalse($order->isError());
        $this->assertEquals(155775, $order->getId());
        $this->assertFalse($order->isError());

        $order = $service->createOrder('token', $orderReq);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertTrue($order->isError());
        $this->assertTrue($order->hasErrorCode(400));

        $order=$service->createOrder('token',$orderReq);
        $this->assertNull($order);

    }
}
