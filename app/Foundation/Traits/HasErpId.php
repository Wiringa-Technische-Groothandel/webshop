<?php

declare(strict_types=1);

namespace WTG\Foundation\Traits;

use Illuminate\Database\Eloquent\Model;
use WTG\Foundation\Api\ErpModelInterface;

/**
 * Has ERP ID trait.
 *
 * @package     WTG\Foundation
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
trait HasErpId
{
    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getErpId(): ?string
    {
        return $this->getAttribute(ErpModelInterface::FIELD_ERP_ID);
    }

    /**
     * @param string $erpId
     * @return Model
     */
    public function setErpId(string $erpId): Model
    {
        return $this->setAttribute(ErpModelInterface::FIELD_ERP_ID, $erpId);
    }
}
