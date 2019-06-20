<?php

declare(strict_types=1);

namespace Okaruto\Space\PostParams;

final class ContactFields
{

    const KEY_EMAIL = 'email';
    const KEY_SUBJECT = 'subject';
    const KEY_MESSAGE = 'message';

    const EXPECTED_KEYS = [
        self::KEY_EMAIL => true,
        self::KEY_SUBJECT => true,
        self::KEY_MESSAGE => true,
    ];

    /** @var array */
    private $fields;

    /** @var null|bool */
    private $valid;

    /** @var string */
    private $email;

    /** @var string */
    private $subject;

    /** @var string */
    private $message;

    /**
     * RemoveOrder constructor.
     *
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param bool $throw
     *
     * @return bool
     * @throws \LogicException
     */
    public function valid(bool $throw = false): bool
    {
        if ($this->valid === null) {
            $this->valid = $this->validate($this->fields);
        }

        if (!$this->valid && $throw) {
            throw new \LogicException('Trying to access invalid ContactFields');
        }

        return $this->valid;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        $this->valid(true);

        return $this->email;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        $this->valid(true);

        return $this->subject;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        $this->valid(true);

        return $this->message;
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    private function validate(array $data): bool
    {
        $valid = false;

        $fields = array_intersect_key($data, self::EXPECTED_KEYS);

        if ($this->checkFields($fields)) {
            $this->email = $fields[self::KEY_EMAIL];
            $this->subject = filter_var($fields[self::KEY_SUBJECT], FILTER_SANITIZE_STRING);
            $this->message = filter_var($fields[self::KEY_MESSAGE], FILTER_SANITIZE_STRING);
            $valid = true;
        }

        return $valid;
    }

    /**
     * @param array $fields
     *
     * @return bool
     */
    protected function checkFields(array $fields): bool
    {
        $valid = empty(array_diff_key(self::EXPECTED_KEYS, $fields))
            && array_reduce(
                $fields,
                function ($carry, $value) {
                    return $carry && !empty($value);
                },
                true
            );

        return $valid && !!filter_var($fields[self::KEY_EMAIL], FILTER_VALIDATE_EMAIL);
    }
}
