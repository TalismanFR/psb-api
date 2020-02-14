<?php


namespace talismanfr\psbbank\shared;


use InvalidArgumentException;

class InnValue
{
    /** @var string */
    private $inn;

    public function __construct(string $inn)
    {
        $this->setInn($inn);
    }

    /**
     * @return string
     */
    public function getInn(): string
    {
        return $this->inn;
    }

    /**
     * @param string $inn
     */
    private function setInn(string $inn): void
    {
        if ($this->isValid($inn)) {
            $this->inn = $inn;
        } else {
            throw new InvalidArgumentException('INN not valid');
        }
    }

    /**
     *
     * @param string $inn 10-ти или 12-ти значное целое число
     * @return  bool    TRUE, если ИНН корректен и FALSE в противном случае
     */
    private function isValid(string $inn): bool
    {
        if (preg_match('/[^0-9]/', $inn)) {
            return false; //'ИНН может состоять только из цифр';
        }

        if (!in_array($inn_length = strlen($inn), [10, 12])) {
            return false;// 'ИНН может состоять только из 10 или 12 цифр';
        }

        switch ($inn_length) {
            case 10:
                $n10 = $this->checkDigit($inn, [2, 4, 10, 3, 5, 9, 4, 6, 8]);
                if ($n10 === (int)$inn{9}) {
                    return true;
                }
                break;
            case 12:
                $n11 = $this->checkDigit($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
                $n12 = $this->checkDigit($inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
                if (($n11 === (int)$inn{10}) && ($n12 === (int)$inn{11})) {
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * @param string $inn
     * @param array $coefficients
     * @return bool
     */
    private function checkDigit(string $inn, array $coefficients): int
    {
        $n = 0;
        foreach ($coefficients as $i => $k) {
            $n += $k * (int)$inn{$i};
        }
        return $n % 11 % 10;
    }

    public function __toString()
    {
        return $this->getInn();
    }

}