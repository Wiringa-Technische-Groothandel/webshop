<?php

declare(strict_types=1);

namespace WTG\Contracts\Models;

/**
 * Block contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface BlockContract
{
    /**
     * Get the block id.
     *
     * @return null|string
     */
    public function getId(): ?string;

    /**
     * Set the block name.
     *
     * @param string $name
     * @return BlockContract
     */
    public function setName(string $name): BlockContract;

    /**
     * Get the block name.
     *
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * Set the block title.
     *
     * @param string $title
     * @return BlockContract
     */
    public function setTitle(string $title): BlockContract;

    /**
     * Get the block title.
     *
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * Set the block content.
     *
     * @param string $content
     * @return BlockContract
     */
    public function setContent(string $content): BlockContract;

    /**
     * Get the block content.
     *
     * @return null|string
     */
    public function getContent(): ?string;
}
