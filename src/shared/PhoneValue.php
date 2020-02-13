<?php


namespace talismanfr\psbbank\shared;


use InvalidArgumentException;

class PhoneValue
{
    private $phone;

    /**
     * PhoneValue constructor.
     * @param string $phone
     * @throws InvalidArgumentException
     */
    public function __construct(string $phone)
    {
        $this->setPhone($phone);
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @throws InvalidArgumentException
     */
    private function setPhone(string $phone): void
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) < 10) {
            throw new InvalidArgumentException('Phone number too short. Minimum 10 numbers');
        } elseif (strlen($phone) == 10) {
            $phone = '+7' . $phone;
        } elseif (strlen($phone) > 11) {
            throw new InvalidArgumentException('Phone number too long. Maximum 11 numbers without symbol `+`');
        } else {
            $phone = '+' . $phone;
        }


        $this->phone = '+7(' . substr($phone, -10, 3) . ')' . substr($phone, -7, 3)
            . '-' . substr($phone, -4, 2) . '-' . substr($phone, -2);
    }

    public function __toString()
    {
        return $this->getPhone();
    }

}