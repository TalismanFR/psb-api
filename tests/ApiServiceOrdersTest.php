<?php


use talismanfr\psbbank\api\Api;
use talismanfr\psbbank\ApiService;
use PHPUnit\Framework\TestCase;
use talismanfr\psbbank\shared\CurlResponse;
use talismanfr\psbbank\shared\PhoneValue;
use talismanfr\psbbank\vo\Order;

class ApiServiceOrdersTest extends TestCase
{

    /** @var Api */
    private $api;

    public function setUp(): void
    {
        $this->api = $this->createStub(Api::class);

        $this->api
            ->method('orders')
            ->will($this->onConsecutiveCalls(
                new CurlResponse(
                    200,
                    '',
                    '{"code":200,"data":[{"id":155775,"company":{"id":72955,"inn":"9724004969","short_name":"test firm","long_name":"test firm","type":10,"extra_data":{"inn":"9724004969"},"created_at":"2020-02-13 13:29:54","updated_at":"2020-02-13 13:29:54","created_by":null},"need_s_schet":false,"need_r_schet":true,"fio":"tes test test","phone":"+7(967)531 91 22","email":"test@test.ru","city_id":119,"comment":"comment","comment_call":"выкл\r\nвыкл","call_id":2116,"status":20,"communication_status":30,"contact_result":null,"recall_date_at":"2020-02-14","recall_time_at":null,"action_id":null,"tariff_id":null,"opening_channel_id":null,"result_offer":null,"meeting_manager_status":null,"memo_author_id":null,"memo_created_at":null,"opening_channel_office_id":null,"contacts":[{"fio":"tes test test","email":"test@test.ru","phone":"+7(967)531 91 22","primary":1}],"created_at":"2020-02-13 13:29:54","bank_reject_reason":null}]}'
                ),
                new CurlResponse(
                    503,
                    '',
                    'not json'
                ),
                new CurlResponse(
                    200,
                    '',
                    '{"errors":{"code":400,"message":"inn: В системе найден дубликат."}}'
                )
            ));
    }

    public function testOrders()
    {
        $service = new ApiService($this->api);
        $orders = $service->orders('token', 1);

        $this->assertIsArray($orders);
        $this->assertArrayHasKey(0, $orders);
        $order = $orders[0];
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(155775, $order->getId());
        $this->assertInstanceOf(PhoneValue::class, $order->getPhone());
        $this->assertEquals('+7(967)531-91-22', $order->getPhone()->getPhone());
        $this->assertEquals($order->getCityId(), 119);
        $this->assertFalse($order->getNeedSSchet());
        $this->assertTrue($order->getNeedRSchet());
        $this->assertEquals($order->getFio(), 'tes test test');
        $this->assertEquals($order->getEmail()->getFullAddress(), 'test@test.ru');
        $this->assertEquals($order->getComment(), 'comment');
        $this->assertEquals($order->getCommentCall(), "выкл\r\nвыкл");
        $this->assertEquals($order->getCallId(), 2116);
        $this->assertEquals($order->getStatus(), 20);
        $this->assertEquals($order->getCommunicationStatus(), 30);
        $this->assertEquals($order->getContactResult(), null);

        $orders = $service->orders('token', 1);
        $this->assertNull($orders);

        $orders = $service->orders('token', '1');
        $this->assertIsArray($orders);
        $this->assertArrayHasKey(0, $orders);
        $order = $orders[0];
        $this->assertTrue($order->isError());
        $this->assertTrue($order->hasErrorMessage('inn: В системе найден дубликат.'));
    }
}
