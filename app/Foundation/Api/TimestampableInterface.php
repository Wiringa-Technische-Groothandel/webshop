<?php

declare(strict_types=1);

namespace WTG\Foundation\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Timestampable interface.
 *
 * @api
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface TimestampableInterface
{
    public const FIELD_CREATED_AT = 'created_at';
    public const FIELD_UPDATED_AT = 'updated_at';

    /**
     * Get the created at timestamp.
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;

    /**
     * Set the created at timestamp.
     *
     * @param Carbon $carbon
     * @return Model
     */
    public function setCreatedAt(Carbon $carbon): Model;

    /**
     * Get the updated at timestamp.
     *
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon;

    /**
     * Set the updated at timestamp.
     *
     * @param Carbon $carbon
     * @return Model
     */
    public function setUpdatedAt(Carbon $carbon): Model;
}
