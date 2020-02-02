<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

/**
 * Role model contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface RoleContract
{
    /**
     * Get the block id.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Set the role name.
     *
     * @param string $name
     * @return RoleContract
     */
    public function setName(string $name): RoleContract;

    /**
     * Get the role name.
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set the role level.
     *
     * @param int $level
     * @return RoleContract
     */
    public function setLevel(int $level): RoleContract;

    /**
     * Get the role level.
     *
     * @return null|int
     */
    public function getLevel(): ?int;
}
