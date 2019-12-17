<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

use Illuminate\Support\Collection;

/**
 * Customer contract.
 *
 * @package     WTG\Contracts
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CustomerContract
{
    /**
     * Get the identifier.
     *
     * @return null|string
     */
    public function getId(): ?int;

    /**
     * Set the username.
     *
     * @param string $username
     * @return CustomerContract
     */
    public function setUsername(string $username): CustomerContract;

    /**
     * Get the username.
     *
     * @return null|string
     */
    public function getUsername(): ?string;

    /**
     * Set the password.
     *
     * @param string $password
     * @return CustomerContract
     */
    public function setPassword(string $password): CustomerContract;

    /**
     * Get the password.
     *
     * @return null|string
     */
    public function getPassword(): ?string;

    /**
     * Set the active.
     *
     * @param bool $active
     * @return CustomerContract
     */
    public function setActive(bool $active): CustomerContract;

    /**
     * Get the active.
     *
     * @return bool
     */
    public function getActive(): bool;

    /**
     * Get the contact.
     *
     * @return ContactContract
     */
    public function getContact(): ContactContract;

    /**
     * Get the company.
     *
     * @return CompanyContract
     */
    public function getCompany(): CompanyContract;

    /**
     * Get the favorites.
     *
     * @return Collection
     */
    public function getFavorites(): Collection;

    /**
     * Check if the customer has set the product as favorite.
     *
     * @param ProductContract $product
     * @return bool
     */
    public function hasFavorite(ProductContract $product): bool;

    /**
     * Add a product as favorite.
     *
     * @param ProductContract $product
     * @return void
     */
    public function addFavorite(ProductContract $product): void;

    /**
     * Add a product as favorite.
     *
     * @param ProductContract $product
     * @return void
     */
    public function removeFavorite(ProductContract $product): void;
}
