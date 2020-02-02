<?php

declare(strict_types=1);

namespace WTG\Foundation\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps as LaravelHasTimestamps;
use Illuminate\Database\Eloquent\Model;
use WTG\Foundation\Api\TimestampableInterface;

/**
 * Has synchronized at trait.
 *
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait HasTimestamps
{
    use LaravelHasTimestamps;

    /**
     * Get the created at timestamp.
     *
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return Carbon::createFromTimestamp(
            $this->getAttribute(TimestampableInterface::FIELD_CREATED_AT)
        );
    }

    /**
     * Set the created at timestamp.
     *
     * @param Carbon $carbon
     * @return Model
     */
    public function setCreatedAt(Carbon $carbon): Model
    {
        return $this->setAttribute(TimestampableInterface::FIELD_CREATED_AT, $carbon);
    }

    /**
     * Get the updated at timestamp.
     *
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return Carbon::createFromTimestamp(
            $this->getAttribute(TimestampableInterface::FIELD_UPDATED_AT)
        );
    }

    /**
     * Set the updated at timestamp.
     *
     * @param Carbon $carbon
     * @return Model
     */
    public function setUpdatedAt(Carbon $carbon): Model
    {
        return $this->setAttribute(TimestampableInterface::FIELD_UPDATED_AT, $carbon);
    }
}
