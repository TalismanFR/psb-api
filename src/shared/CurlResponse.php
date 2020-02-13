<?php


namespace talismanfr\psbbank\shared;


class CurlResponse
{
    private $code;

    private $headers;

    private $body;

    /**
     * CurlResponse constructor.
     * @param $code
     * @param $headers
     * @param $body
     */
    public function __construct(int $code, $headers, ?string $body)
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }
}