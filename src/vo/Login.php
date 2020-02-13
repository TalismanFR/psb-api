<?php


namespace talismanfr\psbbank\vo;


use talismanfr\psbbank\vo\traits\Errors;

class Login
{
    use Errors;

    /** @var int |null */
    private $id;
    /** @var string|null */
    private $token;
    /** @var string |null */
    private $fio;
    /** @var string | null */
    private $agent;

    /**
     * LoginResponse constructor.
     * @param int|null $id
     * @param string|null $token
     * @param string $fio
     * @param string $agent
     * @param array $errors
     */
    public function __construct(?int $id, ?string $token, ?string $fio, ?string $agent, ?array $errors)
    {
        $this->id = $id;
        $this->token = $token;
        $this->fio = $fio;
        $this->agent = $agent;
        $this->errors = $errors;
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromResponseData(array $data): self
    {
        $errors = self::buildErrors($data);
        if ($errors) {
            return new self(null, null, null, null, $errors);
        }

        $data = $data['data'];

        return new self(
            $data['id'],
            $data["access_token"],
            $data['fio'],
            $data['agent'],
            null
        );
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getFio(): ?string
    {
        return $this->fio;
    }

    /**
     * @return string|null
     */
    public function getAgent(): ?string
    {
        return $this->agent;
    }

}