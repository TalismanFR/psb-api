<?php


namespace talismanfr\psbbank\vo;


class Error
{
    /** @var int */
    private $code;

    /** @var string */
    private $message;

    /**
     * Error constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }



}