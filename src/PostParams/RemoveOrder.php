<?php

declare(strict_types=1);

namespace Okaruto\Space\PostParams;

/**
 * Class RemoveOrder
 *
 * @package   Okaruto\Space\PostParams
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class RemoveOrder
{

    /** @var array */
    private $data;

    /**
     * RemoveOrder constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function requested(): bool
    {
        return ($this->data['remove'] ?? 'false') === 'true';
    }
}
