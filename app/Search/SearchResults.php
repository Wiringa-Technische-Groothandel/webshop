<?php

declare(strict_types=1);

namespace WTG\Search;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchResults
{
    protected LengthAwarePaginator $products;
    protected Collection $brands;
    protected Collection $series;
    protected Collection $types;

    /**
     * @return LengthAwarePaginator
     */
    public function getProducts(): LengthAwarePaginator
    {
        return $this->products;
    }

    /**
     * @param LengthAwarePaginator $products
     */
    public function setProducts(LengthAwarePaginator $products): void
    {
        $this->products = $products;
    }

    /**
     * @return Collection
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    /**
     * @param Collection $brands
     */
    public function setBrands(Collection $brands): void
    {
        $this->brands = $brands;
    }

    /**
     * @return Collection
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    /**
     * @param Collection $series
     */
    public function setSeries(Collection $series): void
    {
        $this->series = $series;
    }

    /**
     * @return Collection
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    /**
     * @param Collection $types
     */
    public function setTypes(Collection $types): void
    {
        $this->types = $types;
    }

    public function toArray(): array
    {
        return [
            'products' => $this->products,
            'brands'   => $this->brands,
            'series'   => $this->series,
            'types'    => $this->types,
        ];
    }
}
