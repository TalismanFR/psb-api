<?php


namespace talismanfr\psbbank\vo;


use talismanfr\psbbank\shared\EmailValue;
use talismanfr\psbbank\shared\PhoneValue;
use talismanfr\psbbank\vo\traits\Errors;

class Order
{
    use Errors;

    /** @var int|null */
    private $id;
    /**
     * @var mixed|null
     */
    private $company;

    /** @var bool|null */
    private $needSSchet;
    /** @var bool|null */
    private $needRSchet;
    /** @var string|null */
    private $fio;
    /** @var PhoneValue|null */
    private $phone;
    /** @var EmailValue|null */
    private $email;
    /** @var int|null */
    private $cityId;
    /** @var string|null */
    private $comment;
    /** @var string|null */
    private $commentCall;
    /** @var int|null */
    private $callId;
    /** @var int|null */
    private $status;
    /** @var int|null */
    private $communicationStatus;
    /** @var int|null */
    private $contact_result;

    /**
     * Order constructor.
     * @param int|null $id
     * @param mixed|null $company
     * @param bool|null $needSSchet
     * @param bool|null $needRSchet
     * @param string|null $fio
     * @param PhoneValue|null $phone
     * @param EmailValue|null $email
     * @param int|null $cityId
     * @param string|null $comment
     * @param string|null $commentCall
     * @param int|null $callId
     * @param int|null $status
     * @param int|null $communicationStatus
     * @param int|null $contact_result
     * @param Errors[]|null $erros
     */
    public function __construct(?int $id, $company, ?bool $needSSchet, ?bool $needRSchet,
                                ?string $fio, ?PhoneValue $phone, ?EmailValue $email, ?int $cityId,
                                ?string $comment, ?string $commentCall, ?int $callId, ?int $status,
                                ?int $communicationStatus, ?int $contact_result, ?array $errors)
    {
        $this->id = $id;
        $this->company = $company;
        $this->needSSchet = $needSSchet;
        $this->needRSchet = $needRSchet;
        $this->fio = $fio;
        $this->phone = $phone;
        $this->email = $email;
        $this->cityId = $cityId;
        $this->comment = $comment;
        $this->commentCall = $commentCall;
        $this->callId = $callId;
        $this->status = $status;
        $this->communicationStatus = $communicationStatus;
        $this->contact_result = $contact_result;
        $this->errors = $errors;
    }

    /**
     * @param int $idOrder
     * @return static
     */
    public static function fastCreate(int $idOrder): self
    {
        return new self($idOrder, null, null, null, null, null,
            null, null, null, null,
            null, null, null, null, null);
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromResponseData(array $data): self
    {
        $errors = self::buildErrors($data);
        if ($errors) {
            return new self(null, null, null, null, null, null,
                null, null, null, null,
                null, null, null, null, $errors);
        }

        if (isset($data['data'])) {
            $data = $data['data'];
        }

        if (isset($data['id']) && (count($data) == 1)) {
            return self::fastCreate((int)$data['id']);
        }

        return new self(
            (int)$data['id'],
            $data['company'],
            (bool)$data['need_s_schet'],
            (bool)$data['need_r_schet'],
            $data['fio'],
            new PhoneValue($data['phone']),
            new EmailValue($data['email']),
            (int)$data['city_id'],
            $data['comment'],
            $data['comment_call'],
            (int)$data['call_id'],
            (int)$data['status'],
            (int)$data['communication_status'],
            (int)$data['contact_result'],
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
     * @return bool|null
     */
    public function getNeedSSchet(): ?bool
    {
        return $this->needSSchet;
    }

    /**
     * @return bool|null
     */
    public function getNeedRSchet(): ?bool
    {
        return $this->needRSchet;
    }

    /**
     * @return string|null
     */
    public function getFio(): ?string
    {
        return $this->fio;
    }

    /**
     * @return PhoneValue|null
     */
    public function getPhone(): ?PhoneValue
    {
        return $this->phone;
    }

    /**
     * @return EmailValue|null
     */
    public function getEmail(): ?EmailValue
    {
        return $this->email;
    }

    /**
     * @return int|null
     */
    public function getCityId(): ?int
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

    /**
     * @return string|null
     */
    public function getCommentCall(): ?string
    {
        return $this->commentCall;
    }

    /**
     * @return int|null
     */
    public function getCallId(): ?int
    {
        return $this->callId;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return int|null
     */
    public function getCommunicationStatus(): ?int
    {
        return $this->communicationStatus;
    }

    /**
     * @return int|null
     */
    public function getContactResult(): ?int
    {
        return $this->contact_result;
    }


}