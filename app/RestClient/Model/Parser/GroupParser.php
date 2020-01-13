<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Parser;

use WTG\RestClient\Model\Rest\GetProductGroups\Response\Group;

/**
 * Price parser.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class GroupParser
{
    /**
     * Parse a response product.
     *
     * @param array $item
     * @return Group
     */
    public function parse(array $item): Group
    {
        $price = new Group();
        $price->erpId = $item['id'];
        $price->description = $item['description'];
        $price->code = $item['productGroupCode'];
        $price->level = $item['level'];

        return $price;
    }
}
