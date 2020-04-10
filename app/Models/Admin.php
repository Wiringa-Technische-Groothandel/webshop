<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use WTG\Contracts\Models\AdminContract;

/**
 * Admin model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Admin extends Authenticatable implements AdminContract
{
    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the id.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * Get the username.
     *
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->getAttribute('username');
    }

    /**
     * Set the username.
     *
     * @param string $username
     * @return AdminContract
     */
    public function setUsername(string $username): AdminContract
    {
        return $this->setAttribute('username', $username);
    }

    /**
     * Get the password.
     *
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->getAttribute('password');
    }

    /**
     * Set the password.
     *
     * @param string $password
     * @return AdminContract
     */
    public function setPassword(string $password): AdminContract
    {
        return $this->setAttribute('password', $password);
    }
}
