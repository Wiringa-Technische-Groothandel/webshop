<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use Illuminate\Support\Collection;

/**
 * Order contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface OrderContract
{
    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Get the order items.
     *
     * @return Collection
     */
    public function getItems(): Collection;

    /**
     * Set the customer number.
     *
     * @param  string  $customerNumber
     * @return OrderContract
     */
    public function setCustomerNumber(string $customerNumber): OrderContract;

    /**
     * Get the customer number.
     *
     * @return null|string
     */
    public function getCustomerNumber(): ?string;

    /**
     * Set the company.
     *
     * @param  CompanyContract  $company
     * @return OrderContract
     */
    public function setCompany(CompanyContract $company): OrderContract;

    /**
     * Get the company.
     *
     * @return null|CompanyContract
     */
    public function getCompany(): ?CompanyContract;

    /**
     * Set the name.
     *
     * @param  string  $name
     * @return OrderContract
     */
    public function setName(string $name): OrderContract;

    /**
     * Get the name.
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set the street.
     *
     * @param  string  $street
     * @return OrderContract
     */
    public function setStreet(string $street): OrderContract;

    /**
     * Get the street.
     *
     * @return null|string
     */
    public function getStreet(): ?string;

    /**
     * Set the postcode.
     *
     * @param  null|string  $postcode
     * @return OrderContract
     */
    public function setPostcode(string $postcode): OrderContract;

    /**
     * Get the postcode.
     *
     * @return null|string
     */
    public function getPostcode(): ?string;

    /**
     * Set the city.
     *
     * @param  null|string  $city
     * @return OrderContract
     */
    public function setCity(string $city): OrderContract;

    /**
     * Get the city.
     *
     * @return null|string
     */
    public function getCity(): ?string;

    /**
     * Set the comment.
     *
     * @param  null|string  $comment
     * @return OrderContract
     */
    public function setComment(?string $comment): OrderContract;

    /**
     * Get the comment.
     *
     * @return null|string
     */
    public function getComment(): ?string;

    /**
     * Order total.
     *
     * @return float
     */
    public function getGrandTotal(): float;
}