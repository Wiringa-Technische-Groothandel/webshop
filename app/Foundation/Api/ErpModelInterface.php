<?php

declare(strict_types=1);

namespace WTG\Foundation\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Erp model interface.
 *
 * @api
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface ErpModelInterface
{
    public const FIELD_ERP_ID = 'erp_id';
    public const FIELD_SYNCHRONIZED_AT = 'synchronized_at';

    /**
     * Get the ERP ID.
     *
     * @return null|string
     */
    public function getErpId(): ?string;

    /**
     * Set the ERP ID.
     *
     * @param string $erpId
     * @return Model
     */
    public function setErpId(string $erpId): Model;

    /**
     * Get the synchronized at timestamp.
     *
     * @return Carbon
     */
    public function getSynchronizedAt(): Carbon;

    /**
     * Set the synchronized at timestamp.
     *
     * @param Carbon $carbon
     * @return Model
     */
    public function setSynchronizedAt(Carbon $carbon): Model;
}
