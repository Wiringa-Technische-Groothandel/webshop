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
    public const LINK_TYPE_PRODUCT = 'product';

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
     * Set the image.
     *
     * @param string $image
     * @return Slide
     */
    public function setImage(string $image): Slide
    {
        return $this->setAttribute('image', $image);
    }

    /**
     * Get the image.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->getAttribute('image');
    }

    /**
     * Set the link.
     *
     * @param string|null $link
     * @param string|null $linkType
     * @return Slide
     */
    public function setLink(?string $link, ?string $linkType): Slide
    {
        if ($link && ! in_array($linkType, [static::LINK_TYPE_PRODUCT])) {
            throw new \RuntimeException('Unknown link type: ' . $linkType);
        }

        if (! $link) {
            $link = $linkType = null;
        }

        $this->setAttribute('link', $link);
        $this->setAttribute('link_type', $linkType);

        return $this;
    }

    /**
     * Get the link.
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->getAttribute('link');
    }

    /**
     * Get the link type.
     *
     * @return string|null
     */
    public function getLinkType(): ?string
    {
        return $this->getAttribute('link_type');
    }
}
