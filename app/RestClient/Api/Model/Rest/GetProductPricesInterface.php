<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model\Rest;

use Illuminate\Support\Collection;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * GetProductPrices interface.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface GetProductPricesInterface extends ResponseInterface
{
    /**
     * @return Collection
     */
    public function getPrices(): Collection;

    /**
     * @return Collection
     */
    public function getRawPrices(): Collection;
}
