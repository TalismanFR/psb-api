<?php


namespace talismanfr\psbbank\api\vo;


use InvalidArgumentException;

class LoginRequest
{
    /** @var string */
    private $email;
    /** @var string */
    private $password;

    /**
     * LoginRequest constructor.
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email, string $password)
    {
        $this->checkIsValidEmail($email);
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Check if email is valid
     *
     * @param string $email
     * @throws InvalidArgumentException
     */
    private function checkIsValidEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email ' . $email . ' is not valid');
        }
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}