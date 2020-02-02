<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

/**
 * Admin model contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface AdminContract
{
    /**
     * Get the model id.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Get the username.
     *
     * @return null|string
     */
    public function getUsername(): ?string;

    /**
     * Set the username.
     *
     * @param string $username
     * @return AdminContract
     */
    public function setUsername(string $username): AdminContract;

    /**
     * Get the password.
     *
     * @return null|string
     */
    public function getPassword(): ?string;

    /**
     * Set the password.
     *
     * @param string $password
     * @return AdminContract
     */
    public function setPassword(string $password): AdminContract;
}
