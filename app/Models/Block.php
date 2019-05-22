<?php

namespace WTG\Models;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\BlockContract;
use Illuminate\Database\Eloquent\Builder;

/**
 * Block model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Block extends Model implements BlockContract
{
    /**
     * Name scope.
     *
     * @param  Builder  $query
     * @param  string  $name
     * @return Builder
     */
    public function scopeName(Builder $query, $name): Builder
    {
        return $query->where('name', $name);
    }

    /**
     * Get the block id.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the block name.
     *
     * @param  string  $name
     * @return BlockContract
     */
    public function setName(string $name): BlockContract
    {
        return $this->setAttribute('name', $name);
    }

    /**
     * Get the block name.
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->getAttribute('name');
    }

    /**
     * Set the block title.
     *
     * @param  string  $title
     * @return BlockContract
     */
    public function setTitle(string $title): BlockContract
    {
        return $this->setAttribute('title', $title);
    }

    /**
     * Get the block title.
     *
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->getAttribute('title');
    }

    /**
     * Set the block content.
     *
     * @param  string  $content
     * @return BlockContract
     */
    public function setContent(string $content): BlockContract
    {
        return $this->setAttribute('content', $content);
    }

    /**
     * Get the block content.
     *
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->getAttribute('content');
    }
}