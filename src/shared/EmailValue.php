<?php


namespace talismanfr\psbbank\shared;


use InvalidArgumentException;

class EmailValue
{
    private $userName;
    private $domain;

    public function __construct(string $emailAddress)
    {
        $addressParts = explode('@', $emailAddress);

        if (!is_array($addressParts) || count($addressParts) !== 2) {
            throw new InvalidArgumentException('Given email address could not be parsed');
        }

        $this->userName = $addressParts[0];
        $this->domain = $addressParts[1];

        if (trim($this->domain) === '') {
            throw new InvalidArgumentException('Email domain cannot be empty');
        }
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getNormalizedDomain(): string
    {
        return (string)idn_to_ascii($this->domain, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
    }

    public function getFullAddress(): string
    {
        return $this->userName . '@' . $this->domain;
    }

    public function getNormalizedAddress(): string
    {
        return $this->userName . '@' . $this->getNormalizedDomain();
    }

    public function __toString(): string
    {
        return $this->getFullAddress();
    }


}