<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetPriceApplications;

use WTG\RestClient\Api\Model\RequestInterface;
use WTG\RestClient\Model\Rest\AbstractRequest;

/**
 * GetPriceApplications request model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest implements RequestInterface
{
    /**
     * @var int
     */
    private int $offset;

    /**
     * @var int
     */
    private int $limit;

    /**
     * @var string
     */
    private string $filter;

    /**
     * @var string
     */
    private string $select;

    /**
     * Request constructor.
     *
     * @param int $offset
     * @param int $limit
     * @param string $filter
     * @param string $select
     */
    public function __construct(int $offset = 0, int $limit = 200, string $filter = '', string $select = '*')
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->filter = $filter;
        $this->select = $select;
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return sprintf('%s/productPriceApplications', config('wtg.rest.admin_code'));
    }

    /**
     * @inheritDoc
     */
    public function params(): ?array
    {
        return [
            'select' => $this->select,
            'filter' => $this->filter,
            'offset' => $this->offset,
            'limit'  => $this->limit,
        ];
    }

    /**
     * Set the filter.
     *
     * @param string $filter
     * @return void
     */
    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
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

    /**
     * Set the offset.
     *
     * @param int $offset
     * @return void
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * Set the limit.
     *
     * @param int $limit
     * @return void
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }
}
