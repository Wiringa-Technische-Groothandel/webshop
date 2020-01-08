<?php

declare(strict_types=1);

namespace WTG\Foundation\Traits;

/**
 * Has ID trait.
 *
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait HasId
{
    /**
     * Get the identifier.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }
}
