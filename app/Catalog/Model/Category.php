<?php

declare(strict_types=1);

namespace WTG\Catalog\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use WTG\Catalog\Api\Model\CategoryInterface;
use WTG\Foundation\Api\ErpModelInterface;
use WTG\Foundation\Traits\HasErpId;
use WTG\Foundation\Traits\HasId;
use WTG\Foundation\Traits\HasSynchronizedAt;

/**
 * Category model.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Category extends Model implements CategoryInterface, ErpModelInterface
{
    use HasId;
    use HasErpId;
    use HasSynchronizedAt;

    public const DEFAULT_ID = 0;
    public const ROOT_ERP_ID = 'root';

    /**
     * Parent category.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, self::FIELD_PARENT_ID);
    }

    /**
     * @return null|$this
     */
    public function getParent(): ?self
    {
        return $this->getAttribute('parent');
    }

    /**
     * @param Category $category
     * @return $this
     */
    public function setParent(self $category): self
    {
        $this->parent()->associate($category);
        return $this;
    }

    /**
     * Child categories.
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, self::FIELD_PARENT_ID);
    }

    /**
     * @return Collection|Category[]
     */
    public function getChildren(): Collection
    {
        return $this->getAttribute('children');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): CategoryInterface
    {
        return $this->setAttribute(self::FIELD_NAME, $name);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getAttribute(self::FIELD_NAME);
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): CategoryInterface
    {
        return $this->setAttribute(self::FIELD_CODE, $code);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->getAttribute(self::FIELD_CODE);
    }

    /**
     * @param int $level
     * @return $this
     */
    public function setLevel(int $level): CategoryInterface
    {
        return $this->setAttribute(self::FIELD_LEVEL, $level);
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->getAttribute(self::FIELD_LEVEL);
    }

    /**
     * @param bool $showInMenu
     * @return $this
     */
    public function setShowInMenu(bool $showInMenu): CategoryInterface
    {
        return $this->setAttribute(self::FIELD_SHOW_IN_MENU, $showInMenu);
    }

    /**
     * @return bool
     */
    public function getShowInMenu(): bool
    {
        return (bool) $this->getAttribute(self::FIELD_SHOW_IN_MENU);
    }
}
