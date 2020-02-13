<?php


namespace talismanfr\psbbank;


use Exception;
use talismanfr\psbbank\api\Api;
use talismanfr\psbbank\api\vo\LoginRequest;
use talismanfr\psbbank\api\vo\OrderRequest;
use talismanfr\psbbank\vo\City;
use talismanfr\psbbank\vo\Login;
use talismanfr\psbbank\vo\Order;

class ApiService
{
    /** @var Api */
    private $api;

    /**
     * ApiService constructor.
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * Auth method. Getting token.
     * @param string $email
     * @param $password
     * @return Login|null
     * @throws Exception
     */
    public function login(string $email, $password): ?Login
    {
        $response = $this->api->login(new LoginRequest($email, $password));
        if ($response->getCode() !== 200 || $response->getBody() == null) {
            return null;
        }

        return Login::fromResponseData($this->deserializationResponse($response->getBody()));
    }

    /**
     * @param string $token
     * @return City[]|null
     * @throws Exception
     */
    public function cities(string $token): ?array
    {
        $response = $this->api->cities($token);
        $response = $this->deserializationResponse($response->getBody());
        if (isset($response['data'])) {
            $cities = [];
            foreach ($response['data'] as $datum) {
                $cities[] = City::fromResponseData($datum);
            }
            return $cities;
        } else {
            return [City::fromResponseData($response)];
        }
    }

    /**
     * @param string $token
     * @param OrderRequest $orderRequest
     * @return Order|null
     * @throws Exception
     */
    public function createOrder(string $token, OrderRequest $orderRequest): ?Order
    {
        $response = $this->api->createOrder($token, $orderRequest);
        if ($response->getCode() == 503 || $response->getBody() == null) {
            return null;
        }

        return Order::fromResponseData($this->deserializationResponse($response->getBody()));
    }

    /**
     * @param string $token
     * @param int $page
     * @return Order[]|null
     * @throws Exception
     */
    public function orders(string $token, int $page = 1): ?array
    {
        $response = $this->api->orders($token, $page);
        if ($response->getCode() == 503 || $response->getBody() == null) {
            return null;
        }
        $response = $this->deserializationResponse($response->getBody());

        if (isset($response['data'])) {
            $cities = [];
            foreach ($response['data'] as $datum) {
                $cities[] = Order::fromResponseData($datum);
            }
            return $cities;
        } else {
            return [Order::fromResponseData($response)];
        }
    }

    /**
     * @param string $body
     * @return array
     */
    private function deserializationResponse(string $body): array
    {
        return json_decode($body, true);
    }
}