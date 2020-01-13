<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProductGroups\Response;

/**
 * Group response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Group
{
    /**
     * @var string
     */
    public string $erpId;

    /**
     * @var string
     */
    public string $description;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var int
     */
    public int $level;
}
