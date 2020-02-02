<?php

declare(strict_types=1);

namespace WTG\Foundation\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as LaravelSoftDeletes;
use WTG\Foundation\Api\SoftDeletableInterface;

/**
 * Soft deletes trait.
 *
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait SoftDeletes
{
    use LaravelSoftDeletes;

    /**
     * @return null|Carbon
     */
    public function getDeletedAt(): ?Carbon
    {
        $deletedAt = $this->getAttribute(SoftDeletableInterface::FIELD_DELETED_AT);

        return $deletedAt ? Carbon::createFromTimestamp($deletedAt) : null;
    }

    /**
     * @param null|Carbon $carbon
     * @return Model
     */
    public function setDeletedAt(?Carbon $carbon): Model
    {
        return $this->setAttribute(SoftDeletableInterface::FIELD_DELETED_AT, $carbon);
    }
}
