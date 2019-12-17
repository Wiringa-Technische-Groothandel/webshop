<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model\Rest;

use Illuminate\Support\Collection;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * GetProducts interface.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface GetProductsInterface extends ResponseInterface
{
    /**
     * @return Collection
     */
    public function getProducts(): Collection;

    /**
     * @return Collection
     */
    public function getRawProducts(): Collection;
}
