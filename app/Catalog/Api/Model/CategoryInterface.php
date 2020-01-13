<?php

declare(strict_types=1);

namespace WTG\Catalog\Api\Model;

/**
 * Category model interface.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
interface CategoryInterface
{
    public const FIELD_ID = 'id';
    public const FIELD_PARENT_ID = 'parent_id';
    public const FIELD_NAME = 'name';
    public const FIELD_LEVEL = 'level';
    public const FIELD_SHOW_IN_MENU = 'show_in_menu';
    public const FIELD_CODE = 'code';

    /**
     * @param string $name
     * @return CategoryInterface
     */
    public function setName(string $name): CategoryInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $code
     * @return CategoryInterface
     */
    public function setCode(string $code): CategoryInterface;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param int $level
     * @return CategoryInterface
     */
    public function setLevel(int $level): CategoryInterface;

    /**
     * @return int
     */
    public function getLevel(): int;

    /**
     * @param bool $showInMenu
     * @return CategoryInterface
     */
    public function setShowInMenu(bool $showInMenu): CategoryInterface;

    /**
     * @return bool
     */
    public function getShowInMenu(): bool;
}
