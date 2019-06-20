<?php

declare(strict_types=1);

namespace Okaruto\Space\PostParams;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class StatusChange
 *
 * @package   Okaruto\Space\PostParams
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class StatusChange
{

    /** @var array */
    private $data;

    /** @var string */
    private $status;

    /**
     * StatusChange constructor.
     *
     * @param array  $data
     * @param string $status
     */
    public function __construct(array $data, string $status)
    {
        $this->status = $status;
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function changed(): bool
    {
        return ($this->data['status'] ?? $this->status) !== $this->status;
    }
}
