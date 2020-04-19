<?php

declare(strict_types=1);

namespace WTG\Search;

/**
 * Search filter.
 *
 * @package     WTG\Search
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SearchFilter
{
    protected string $query;
    protected string $brand;
    protected string $series;
    protected string $type;

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return string
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * @param string $series
     */
    public function setSeries(string $series): void
    {
        $this->series = $series;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'query'  => $this->query,
            'brand'  => $this->brand,
            'series' => $this->series,
            'type'   => $this->type
        ];
    }
}
