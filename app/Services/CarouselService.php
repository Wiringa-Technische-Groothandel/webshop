<?php

namespace WTG\Services;

use WTG\Models\Carousel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Application;
use WTG\Contracts\Models\CarouselContract;
use Illuminate\Database\Connection as Database;
use WTG\Contracts\Services\CarouselServiceContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Carousel service.
 *
 * @package     WTG\Services
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CarouselService implements CarouselServiceContract
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Database
     */
    protected $db;

    /**
     * CarouselService constructor.
     *
     * @param  Application  $app
     * @param  Database  $db
     */
    public function __construct(Application $app, Database $db)
    {
        $this->app = $app;
        $this->db = $db;
    }

    /**
     * Get a collection of ordered carousel models.
     *
     * @return Collection
     */
    public function getOrderedSlides(): Collection
    {
        return $this->app->make(CarouselContract::class)->orderBy('order', 'asc')->get();
    }

    /**
     * Get the current slide count.
     *
     * @return int
     */
    public function getSlideCount(): int
    {
        return $this->app->make(CarouselContract::class)->count();
    }

    /**
     * Create a new slide.
     *
     * @param  string  $title
     * @param  string  $caption
     * @param  UploadedFile  $image
     * @return CarouselContract
     * @throws \Exception
     */
    public function createSlide(string $title, string $caption, UploadedFile $image): CarouselContract
    {
        $this->db->beginTransaction();

        try {
            /** @var Carousel $slide */
            $slide = $this->app->make(CarouselContract::class);

            $slide->setImage($image->getClientOriginalName());
            $slide->setTitle($title);
            $slide->setCaption($caption);
            $slide->setOrder($this->getSlideCount());

            $slide->save();

            $image->move(public_path('img/carousel'), $image->getClientOriginalName());
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return $slide;
    }

    /**
     * Update a slide.
     *
     * @param  int  $slideId
     * @param  null|string  $title
     * @param  null|string  $caption
     * @param  null|int  $order
     * @return CarouselContract
     * @throws \Exception
     * @throws ModelNotFoundException
     */
    public function updateSlide(int $slideId, ?string $title = null, ?string $caption = null, ?int $order = null): CarouselContract
    {
        /** @var Carousel $slide */
        $slide = $this->app->make(CarouselContract::class)->findOrFail($slideId);

        $this->db->beginTransaction();

        try {
            if ($title !== null) {
                $slide->setTitle($title);
            }

            if ($caption !== null) {
                $slide->setCaption($caption);
            }

            if ($order !== null) {
                $slide->setOrder($order);
            }

            $slide->save();
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return $slide;
    }

    /**
     * Delete a slide.
     *
     * @param  int  $slideId
     * @return bool
     * @throws \Exception
     */
    public function deleteSlide(int $slideId): bool
    {
        /** @var Carousel $slide */
        $slide = $this->app->make(CarouselContract::class)->findOrFail($slideId);

        $this->db->beginTransaction();

        try {
            $slide->delete();
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        return true;
    }
}