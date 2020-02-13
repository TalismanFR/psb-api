<?php


namespace talismanfr\psbbank\vo\traits;


use talismanfr\psbbank\vo\Error;

trait Errors
{
    /**
     * @var Error[] | null
     */
    private $errors;

    /**
     * @return Error[]
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

    /**
     * @param int $code
     * @return bool
     */
    public function hasErrorCode(int $code): bool
    {
        foreach ($this->errors as $error) {
            if ($error->getCode() === $code) {
                return true;
            }
        }
        return false;
    }

    public function isError(): bool
    {
        return !empty($this->errors);
    }

    /**
     * @param $data
     * @return Error[]|null
     */
    private static function buildErrors($data): ?array
    {
        $errors = [];
        if (isset($data['errors'])) {
            if (isset($data['errors']['code'])) {
                $errors[] = new Error($data['errors']['code'], $data['errors']['message']);
            } else {
                foreach ($data as $datum) {
                    $errors[] = new Error($datum['errors']['code'], $datum['errors']['message']);
                }
            }
        } elseif (isset($data['code']) && $data['code'] != 200) {
            $errors[] = new Error($data['code'], $data['message']);
        }

        if (count($errors) == 0) {
            return null;
        }

        return $errors;
    }
}