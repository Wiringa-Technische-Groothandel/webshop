<?php

declare(strict_types=1);

namespace WTG\Managers;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Throwable;
use WTG\Models\Slide;

/**
 * Carousel manager.
 *
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class CarouselManager
{
    protected DatabaseManager $databaseManager;
    protected FilesystemManager $filesystemManager;

    /**
     * CarouselService constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param FilesystemManager $filesystemManager
     */
    public function __construct(
        DatabaseManager $databaseManager,
        FilesystemManager $filesystemManager
    ) {
        $this->databaseManager = $databaseManager;
        $this->filesystemManager = $filesystemManager;
    }

    /**
     * Get a collection of ordered carousel models.
     *
     * @return Collection
     */
    public function getOrderedSlides(): Collection
    {
        return Slide::query()->orderBy('order')->get();
    }

    /**
     * Set a slide's order.
     *
     * @param Slide $slide
     * @param int $order
     * @return Slide
     */
    public function setSlideOrder(Slide $slide, int $order): Slide
    {
        $slide->setOrder($order);

        return $slide;
    }

    /**
     * Set a slide's title.
     *
     * @param Slide $slide
     * @param string $title
     * @return Slide
     */
    public function setSlideTitle(Slide $slide, string $title): Slide
    {
        $slide->setTitle($title);

        return $slide;
    }

    /**
     * Set a slide's caption.
     *
     * @param Slide $slide
     * @param string $caption
     * @return Slide
     */
    public function setSlideCaption(Slide $slide, string $caption): Slide
    {
        $slide->setCaption($caption);

        return $slide;
    }

    /**
     * Find a slide by id.
     *
     * @param int $slideId
     * @return Slide
     * @throws ModelNotFoundException
     */
    public function findSlide(int $slideId): Slide
    {
        /** @var Slide $carousel */
        $carousel = Slide::query()->findOrFail($slideId);

        return $carousel;
    }

    /**
     * Create a new slide.
     *
     * @param string $title
     * @param string $caption
     * @param UploadedFile $image
     * @return Slide
     * @throws Throwable
     */
    public function createSlide(string $title, string $caption, UploadedFile $image): Slide
    {
        try {
            $this->databaseManager->beginTransaction();

            $slide = new Slide();

            $slide->setImage($image->getClientOriginalName());
            $slide->setTitle($title);
            $slide->setCaption($caption);
            $slide->setOrder($this->getSlideCount());

            $slide->saveOrFail();

            if (! $this->filesystemManager->exists(storage_path('app/public/uploads/images/carousel'))) {
                $this->filesystemManager->makeDirectory(storage_path('app/public/uploads/images/carousel'));
            }

            Image::make($image)
                ->resize(
                    null,
                    300,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )
                ->encode('jpg')
                ->save(sprintf(storage_path('app/public/uploads/images/carousel/%s'), $image->getClientOriginalName()));

            $this->databaseManager->commit();
        } catch (Throwable $throwable) {
            $this->databaseManager->rollBack();

            throw $throwable;
        }

        return $slide;
    }

    /**
     * Get the current slide count.
     *
     * @return int
     */
    public function getSlideCount(): int
    {
        return Slide::query()->count();
    }

    /**
     * Delete a slide.
     *
     * @param Slide $slide
     * @return bool
     * @throws Throwable
     */
    public function deleteSlide(Slide $slide): bool
    {
        try {
            $this->databaseManager->beginTransaction();

            $filePath = sprintf(storage_path('app/public/uploads/images/carousel/%s'), $slide->getImage());

            $slide->delete();

            if ($this->filesystemManager->exists($filePath)) {
                $this->filesystemManager->delete($filePath);
            }

            $this->databaseManager->commit();
        } catch (Throwable $throwable) {
            $this->databaseManager->rollBack();

            throw $throwable;
        }

        return true;
    }

    /**
     * Save a slide.
     *
     * @param Slide $slide
     * @return Slide
     * @throws Throwable
     */
    public function saveSlide(Slide $slide): Slide
    {
        try {
            $this->databaseManager->beginTransaction();

            $slide->saveOrFail();

            $this->databaseManager->commit();
        } catch (Throwable $throwable) {
            $this->databaseManager->rollBack();

            throw $throwable;
        }

        return $slide;
    }
}
