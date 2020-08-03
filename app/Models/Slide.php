<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Slide model.
 *
 * @package     WTG\Carousel
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Slide extends Model
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
     * @param string $title
     * @return Slide
     */
    public function setTitle(string $title): Slide
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
     * @param string $caption
     * @return Slide
     */
    public function setCaption(string $caption): Slide
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
     * @param int $order
     * @return Slide
     */
    public function setOrder(int $order): Slide
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
     * @param string $image
     * @return Slide
     */
    public function setImage(string $image): Slide
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
