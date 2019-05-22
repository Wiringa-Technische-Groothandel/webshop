<?php

namespace WTG\Contracts\Models;

/**
 * Carousel contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CarouselContract
{
    /**
     * Slide identifier.
     *
     * @return null|int
     */
    public function getId(): ?int;

    /**
     * Set the title.
     *
     * @param  string  $title
     * @return CarouselContract
     */
    public function setTitle(string $title): CarouselContract;

    /**
     * Get the title.
     *
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * Set the caption.
     *
     * @param  string  $caption
     * @return CarouselContract
     */
    public function setCaption(string $caption): CarouselContract;

    /**
     * Get the caption.
     *
     * @return null|string
     */
    public function getCaption(): ?string;

    /**
     * Set the order.
     *
     * @param  int  $order
     * @return CarouselContract
     */
    public function setOrder(int $order): CarouselContract;

    /**
     * Get the order.
     *
     * @return int
     */
    public function getOrder(): int;

    /**
     * Set the order.
     *
     * @param  string  $image
     * @return CarouselContract
     */
    public function setImage(string $image): CarouselContract;

    /**
     * Get the order.
     *
     * @return string
     */
    public function getImage(): string;
}