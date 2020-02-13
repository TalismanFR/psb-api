<?php


namespace talismanfr\psbbank\api\vo;


use talismanfr\psbbank\shared\EmailValue;
use talismanfr\psbbank\shared\InnValue;
use talismanfr\psbbank\shared\PhoneValue;

class OrderRequest
{
    /** @var InnValue */
    private $inn;
    /** @var string */
    private $name;
    /** @var bool */
    private $needSSchet;
    /** @var bool */
    private $needRSchet;
    /** @var string */
    private $fio;
    /** @var PhoneValue */
    private $phone;
    /** @var EmailValue */
    private $email;
    /** @var int */
    private $cityId;
    /** @var string|null */
    private $comment;

    /**
     * OrderRequest constructor.
     * @param InnValue $inn
     * @param string $name
     * @param bool $needSSchet
     * @param bool $needRSchet
     * @param string $fio
     * @param PhoneValue $phone
     * @param EmailValue $email
     * @param int $cityId
     * @param string|null $comment
     */
    public function __construct(InnValue $inn, string $name, bool $needSSchet, bool $needRSchet, string $fio, PhoneValue $phone, EmailValue $email, int $cityId, ?string $comment)
    {
        $this->inn = $inn;
        $this->name = $name;
        $this->needSSchet = $needSSchet;
        $this->needRSchet = $needRSchet;
        $this->fio = $fio;
        $this->phone = $phone;
        $this->email = $email;
        $this->cityId = $cityId;
        $this->comment = $comment;
    }

    /**
     * @return InnValue
     */
    public function getInn(): InnValue
    {
        return $this->inn;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isNeedSSchet(): bool
    {
        return $this->needSSchet;
    }

    /**
     * @return bool
     */
    public function isNeedRSchet(): bool
    {
        return $this->needRSchet;
    }

    /**
     * @return string
     */
    public function getFio(): string
    {
        return $this->fio;
    }

    /**
     * @return PhoneValue
     */
    public function getPhone(): PhoneValue
    {
        return $this->phone;
    }

    /**
     * @return EmailValue
     */
    public function getEmail(): EmailValue
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getCityId(): int
    {
        return $this->cityId;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function toArray(): array
    {
        return [
            'inn' => $this->getInn()->getInn(),
            'name' => $this->getName(),
            'need_s_schet' => $this->isNeedSSchet(),
            'need_r_schet' => $this->isNeedRSchet(),
            'fio' => $this->getFio(),
            'phone' => $this->getPhone()->getPhone(),
            'email' => $this->getEmail()->getFullAddress(),
            'city_id' => $this->getCityId(),
            'comment' => $this->getComment(),
        ];
    }


}