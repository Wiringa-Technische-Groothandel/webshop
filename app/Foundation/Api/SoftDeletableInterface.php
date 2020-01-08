<?php

declare(strict_types=1);

namespace WTG\Foundation\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Soft deletable interface.
 *
 * @api
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface SoftDeletableInterface
{
    public const FIELD_DELETED_AT = 'deleted_at';

    /**
     * Get soft deleted timestamp.
     *
     * @return null|Carbon
     */
    public function getDeletedAt(): ?Carbon;

    /**
     * Set soft deleted timestamp.
     *
     * @param Carbon $carbon
     * @return Model
     */
    public function setDeletedAt(Carbon $carbon): Model;
}
