<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetProduct;

use WTG\RestClient\Api\Model\RequestInterface;
use WTG\RestClient\Model\Rest\AbstractRequest;

/**
 * GetProduct request model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest implements RequestInterface
{
    private string $id;

    private string $select;

    /**
     * Request constructor.
     *
     * @param string $id
     * @param string $select
     */
    public function __construct(string $id, string $select = '*')
    {
        $this->id = $id;
        $this->select = $select;
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return sprintf('%s/product/%s', config('wtg.rest.admin_code'), $this->id);
    }

    /**
     * @inheritDoc
     */
    public function params(): ?array
    {
        return [
            'select' => $this->select,
        ];
    }

    /**
     * Set the select.
     *
     * @param string $select
     * @return void
     */
    public function setSelect(string $select): void
    {
        $this->select = $select;
    }
}
