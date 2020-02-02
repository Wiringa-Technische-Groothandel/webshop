<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model\Rest;

use Illuminate\Support\Collection;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * GetProductStocks interface.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface GetProductStocksInterface extends ResponseInterface
{
    /**
     * @return Collection
     */
    public function getStocks(): Collection;

    /**
     * @return Collection
     */
    public function getRawStocks(): Collection;
}
