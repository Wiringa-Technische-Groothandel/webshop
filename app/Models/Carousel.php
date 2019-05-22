<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use WTG\Contracts\Models\CarouselContract;

/**
 * Carousel model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Carousel extends Model implements CarouselContract
{
    /**
     * @var string
     */
    public $table = 'carousel';

    /**
     * Slide identifier.
     *
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * Set the title.
     *
     * @param  string  $title
     * @return CarouselContract
     */
    public function setTitle(string $title): CarouselContract
    {
        return $this->setAttribute('title', $title);
    }

    /**
     * Get the title.
     *
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->getAttribute('title');
    }

    /**
     * Set the caption.
     *
     * @param  string  $caption
     * @return CarouselContract
     */
    public function setCaption(string $caption): CarouselContract
    {
        return $this->setAttribute('caption', $caption);
    }

    /**
     * Get the caption.
     *
     * @return null|string
     */
    public function getCaption(): ?string
    {
        return $this->getAttribute('caption');
    }

    /**
     * Set the order.
     *
     * @param  int  $order
     * @return CarouselContract
     */
    public function setOrder(int $order): CarouselContract
    {
        return $this->setAttribute('order', $order);
    }

    /**
     * Get the order.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return $this->getAttribute('order');
    }

    /**
     * Set the order.
     *
     * @param  string  $image
     * @return CarouselContract
     */
    public function setImage(string $image): CarouselContract
    {
        return $this->setAttribute('image', $image);
    }

    /**
     * Get the order.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->getAttribute('image');
    }
}