<?php

declare(strict_types=1);

namespace WTG\RestClient\Api\Model\Rest;

use Illuminate\Support\Collection;
use WTG\RestClient\Api\Model\ResponseInterface;

/**
 * GetProductGroups interface.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface GetProductGroupsInterface extends ResponseInterface
{
    /**
     * @return Collection
     */
    public function getGroups(): Collection;

    /**
     * @return Collection
     */
    public function getRawGroups(): Collection;
}
