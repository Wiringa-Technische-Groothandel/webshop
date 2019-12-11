<?php

declare(strict_types=1);

namespace WTG\Models;

use WTG\Contracts\Models\RoleContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Rule model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Role extends Model implements RoleContract
{
    const ROLE_MANAGER = 200;
    const ROLE_USER = 100;

    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Query scope for level field.
     *
     * @param  Builder  $query
     * @param  int  $level
     * @return Builder
     */
    public function scopeLevel(Builder $query, int $level): Builder
    {
        return $query->where('level', $level);
    }

    /**
     * Get the block id.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the role name.
     *
     * @param  string  $name
     * @return RoleContract
     */
    public function setName(string $name): RoleContract
    {
        return $this->setAttribute('name', $name);
    }

    /**
     * Get the role name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }

    /**
     * Set the role level.
     *
     * @param  int  $level
     * @return RoleContract
     */
    public function setLevel(int $level): RoleContract
    {
        return $this->setAttribute('level', $level);
    }

    /**
     * Get the role level.
     *
     * @return null|int
     */
    public function getLevel(): ?int
    {
        return $this->getAttribute('level');
    }
}