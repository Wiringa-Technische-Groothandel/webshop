<?php

declare(strict_types=1);

namespace WTG\Foundation\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use WTG\Foundation\Api\ErpModelInterface;

/**
 * Has synchronized at trait.
 *
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait HasSynchronizedAt
{
    /**
     * Get the synchronized at timestamp.
     *
     * @return Carbon
     */
    public function getSynchronizedAt(): Carbon
    {
        return Carbon::createFromTimestamp(
            $this->getAttribute(ErpModelInterface::FIELD_SYNCHRONIZED_AT)
        );
    }

    /**
     * Set the synchronized at timestamp.
     *
     * @param Carbon $carbon
     * @return Model
     */
    public function setSynchronizedAt(Carbon $carbon): Model
    {
        return $this->setAttribute(ErpModelInterface::FIELD_SYNCHRONIZED_AT, $carbon);
    }
}
