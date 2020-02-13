<?php

namespace api;

use talismanfr\psbbank\api\Api;
use talismanfr\psbbank\ApiService;
use PHPUnit\Framework\TestCase;
use talismanfr\psbbank\shared\CurlResponse;
use talismanfr\psbbank\vo\City;

class ApiServiceCitiesTest extends TestCase
{
    /** @var Api */
    private $api;

    public function setUp(): void
    {
        $this->api = $this->createStub(Api::class);

        $this->api
            ->method('cities')
            ->will($this->onConsecutiveCalls(
                new CurlResponse(
                    401,
                    '',
                    '{"name":"Unauthorized","message":"Your request was made with invalid credentials.","code":0,"status":401}'
                ),
                new CurlResponse(
                    200,
                    '',
                    '{"code":200,"data":[{"id":119,"city_id":"119-103-5","city_name":"Абакан","city_timezone":"МСК+4","region":"Республика Хакасия"},{"id":75,"city_id":"75-102-4","city_name":"Альметьевск","city_timezone":"МСК","region":"Республика Татарстан"}]}'
                )
            ));
    }

    public function testCities()
    {
        $service = new ApiService($this->api);
        $cities = $service->cities('123');
        $this->assertIsArray($cities);
        $er = $cities[0];
        $this->assertInstanceOf(City::class, $er);
        $this->assertTrue($er->isError());

        $cities = $service->cities('123');
        $this->assertIsArray($cities);
        $city = $cities[0];
        $this->assertInstanceOf(City::class, $city);
        $this->assertFalse($city->isError());
        $this->assertEquals($city->getId(), 119);
        $this->assertEquals($city->getCityId(), '119-103-5');
        $this->assertEquals($city->getCityName(), 'Абакан');
        $this->assertEquals($city->getCityTimezone(), 'МСК+4');
        $this->assertEquals($city->getRegion(), 'Республика Хакасия');
    }
}
