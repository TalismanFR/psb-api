<?php


namespace talismanfr\psbbank\vo;


use talismanfr\psbbank\vo\traits\Errors;

class City
{
    use Errors;

    /**
     * @var int|null
     */
    private $id;
    /** @var string|null */
    private $cityId;
    /** @var string|null */
    private $cityName;
    /** @var string|null */
    private $cityTimezone;
    /** @var string|null */
    private $region;

    /**
     * City constructor.
     * @param int|null $id
     * @param string|null $cityId
     * @param string|null $cityName
     * @param string|null $cityTimezone
     * @param string|null $region
     * @param array|null $errors
     */
    public function __construct(?int $id, ?string $cityId, ?string $cityName, ?string $cityTimezone, ?string $region, ?array $errors)
    {
        $this->id = $id;
        $this->cityId = $cityId;
        $this->cityName = $cityName;
        $this->cityTimezone = $cityTimezone;
        $this->region = $region;
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
            return new self(null, null, null, null, null, $errors);
        }

        return new self(
            $data['id'],
            $data["city_id"],
            $data['city_name'],
            $data['city_timezone'],
            $data['region'],
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
    public function getCityId(): ?string
    {
        return $this->cityId;
    }

    /**
     * @return string|null
     */
    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    /**
     * @return string|null
     */
    public function getCityTimezone(): ?string
    {
        return $this->cityTimezone;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }


}