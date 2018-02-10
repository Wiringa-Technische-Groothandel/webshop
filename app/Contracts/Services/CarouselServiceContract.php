<?php

namespace WTG\Contracts\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use WTG\Contracts\Models\CarouselContract;

/**
 * Carousel service contract.
 *
 * @package     WTG\Contracts
 * @subpackage  Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
interface CarouselServiceContract
{
    /**
     * Get a collection of ordered carousel models.
     *
     * @return Collection
     */
    public function getOrderedSlides(): Collection;

    /**
     * Get the current slide count.
     *
     * @return int
     */
    public function getSlideCount(): int;

    /**
     * Create a new slide.
     *
     * @param  string  $title
     * @param  string  $caption
     * @param  UploadedFile  $image
     * @return CarouselContract
     */
    public function createSlide(string $title, string $caption, UploadedFile $image): CarouselContract;

    /**
     * Update a slide.
     *
     * @param  int  $slideId
     * @param  null|string  $title
     * @param  null|string  $caption
     * @param  null|int  $order
     * @return CarouselContract
     */
    public function updateSlide(int $slideId, ?string $title = null, ?string $caption = null, ?int $order = null): CarouselContract;

    /**
     * Delete a slide.
     *
     * @param  int  $slideId
     * @return bool
     */
    public function deleteSlide(int $slideId): bool;
}