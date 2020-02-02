<?php

declare(strict_types=1);

namespace WTG\Catalog;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use Throwable;
use WTG\Catalog\Model\Pack;
use WTG\Catalog\Model\PackProduct;

/**
 * Pack manager.
 *
 * @package     WTG\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PackManager
{
    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * PackManager constructor.
     *
     * @param DatabaseManager $databaseManager
     * @param ProductManager $productManager
     */
    public function __construct(DatabaseManager $databaseManager, ProductManager $productManager)
    {
        $this->databaseManager = $databaseManager;
        $this->productManager = $productManager;
    }

    /**
     * Get ordered packs.
     *
     * @return Collection
     */
    public function getPacks(): Collection
    {
        return Pack::query()->with('product', 'items.product')->get();
    }

    /**
     * Create a new pack.
     *
     * @param null|string $sku
     * @return Pack
     * @throws Throwable
     */
    public function createPack(?string $sku): Pack
    {
        $product = $this->productManager->find($sku);

        $pack = new Pack();
        $pack->setProduct($product);

        return $this->savePack($pack);
    }

    /**
     * Save a pack model.
     *
     * @param Pack $pack
     * @return Pack
     * @throws Throwable
     */
    public function savePack(Pack $pack): Pack
    {
        try {
            $this->databaseManager->beginTransaction();
            $pack->saveOrFail();
            $this->databaseManager->commit();
        } catch (Throwable $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }

        return $pack;
    }

    /**
     * Find a pack by id.
     *
     * @param int $id
     * @return Pack
     */
    public function findPack(int $id): Pack
    {
        return Pack::query()->with('product', 'items.product')->findOrFail($id);
    }

    /**
     * Find a pack product by id.
     *
     * @param int $id
     * @return PackProduct
     */
    public function findPackItem(int $id): PackProduct
    {
        return PackProduct::query()->with('product')->findOrFail($id);
    }

    /**
     * Add a product to a pack.
     *
     * @param Pack $pack
     * @param string $sku
     * @param int $amount
     * @return PackProduct
     * @throws Throwable
     */
    public function addProductToPack(Pack $pack, string $sku, int $amount): PackProduct
    {
        $product = $this->productManager->find($sku);

        $packProduct = new PackProduct();
        $packProduct->setPack($pack);
        $packProduct->setAmount($amount);
        $packProduct->setProduct($product);

        try {
            $this->databaseManager->beginTransaction();
            $packProduct->saveOrFail();
            $this->databaseManager->commit();
        } catch (Throwable $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }

        return $packProduct;
    }

    /**
     * Delete a pack.
     *
     * @param Pack $pack
     * @return void
     * @throws Throwable
     */
    public function deletePack(Pack $pack): void
    {
        try {
            $this->databaseManager->beginTransaction();
            $pack->delete();
            $this->databaseManager->commit();
        } catch (Throwable $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }
    }

    /**
     * Delete a pack product.
     *
     * @param PackProduct $packItem
     * @return void
     * @throws Throwable
     */
    public function deletePackItem(PackProduct $packItem): void
    {
        try {
            $this->databaseManager->beginTransaction();
            $packItem->delete();
            $this->databaseManager->commit();
        } catch (Throwable $e) {
            $this->databaseManager->rollBack();

            throw $e;
        }
    }
}
