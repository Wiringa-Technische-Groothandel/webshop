<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model\Rest;

use Illuminate\Support\Collection;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * GetPriceFactors interface.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface GetPriceFactorsInterface extends ResponseInterface
{
    /**
     * @return Collection
     */
    public function getPriceFactors(): Collection;

    /**
     * @return Collection
     */
    public function getRawPriceFactors(): Collection;
}
