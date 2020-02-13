<?php


namespace talismanfr\psbbank\api;


use Exception;
use talismanfr\psbbank\api\vo\LoginRequest;
use talismanfr\psbbank\api\vo\OrderRequest;
use talismanfr\psbbank\shared\CurlResponse;

class Api
{
    /** @var string */
    private $url;

    private const URL_LOGIN = 'user/login';
    private const URL_CITIES = 'cities';
    private const URL_ORDER = 'orders';

    /**
     * Api constructor.
     * @param string $url
     */
    public function __construct(string $url = 'https://api.lk.psbank.ru/fo/v1.0.0/')
    {
        $this->url = $url;
    }

    /**
     * @param LoginRequest $loginRequest
     * @return CurlResponse
     * @throws Exception
     */
    public function login(LoginRequest $loginRequest): CurlResponse
    {
        return $this->send($this->url . self::URL_LOGIN, json_encode($loginRequest->toArray(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param $token
     * @return CurlResponse
     * @throws Exception
     */
    public function cities(string $token): CurlResponse
    {
        return $this->send($this->url . self::URL_CITIES . '?access-token=' . $token);
    }

    /**
     * @param string $token
     * @param OrderRequest $orderRequest
     * @return CurlResponse
     * @throws Exception
     */
    public function createOrder(string $token, OrderRequest $orderRequest): CurlResponse
    {
        return $this->send($this->url . self::URL_ORDER . '?access-token='
            . $token, json_encode($orderRequest->toArray(), JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param string $token
     * @param int $page
     * @return CurlResponse
     * @throws Exception
     */
    public function orders(string $token, int $page = 0): CurlResponse
    {
        return $this->send($this->url . self::URL_ORDER . '?access-token=' . $token . '&page=' . $page);
    }

    /**
     * @param string $token
     * @param int $idOrder
     * @return CurlResponse
     * @throws Exception
     */
    public function order(string $token, int $idOrder): CurlResponse
    {
        return $this->send($this->url . self::URL_ORDER . '/' . $idOrder . '?access-token=' . $token);
    }

    /**
     * @param string $url
     * @param string $data
     * @return CurlResponse
     * @throws Exception
     */
    private function send(string $url, ?string $data = null): CurlResponse
    {
        $curl = curl_init();
        if (!$curl) {
            throw new Exception('Curl not initialize');
        }

        try {
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_VERBOSE => false,
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json"
                ],
            ]);

            if ($data !== null) {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            $response = curl_exec($curl);

            $headers_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $res = new CurlResponse(
                curl_getinfo($curl, CURLINFO_HTTP_CODE),
                mb_substr($response, 0, $headers_size, 'utf-8'),
                mb_substr($response, $headers_size, null, 'utf-8')
            );

            return $res;
        } catch (Exception $e) {
            throw $e;
        } finally {
            curl_close($curl);
        }
    }
}