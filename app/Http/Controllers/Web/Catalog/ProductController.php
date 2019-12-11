<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Catalog;

use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use WTG\Http\Controllers\Controller;
use WTG\Repositories\ProductRepository;

/**
 * Product controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductController constructor.
     *
     * @param  ViewFactory  $view
     * @param  ProductRepository  $productRepository
     */
    public function __construct(ViewFactory $view, ProductRepository $productRepository)
    {
        parent::__construct($view);

        $this->productRepository = $productRepository;
    }

    /**
     * Product detail page.
     *
     * @param  string  $sku
     * @return \Illuminate\View\View
     */
    public function getAction(string $sku): View
    {
        $product = $this->productRepository->findBySku($sku);
        $previousUrl = $this->getAssortmentUrl();

        if (! $product) {
            abort(404, __("Er is geen product gevonden met productnummer :sku", ['sku' => $sku]));
        }

        return view('pages.catalog.product', compact('product', 'previousUrl'));
    }

    /**
     * Get the last url or return the assortment url.
     *
     * @return string
     */
    protected function getAssortmentUrl(): string
    {
        $lastUrl = url()->previous();

        if (strpos($lastUrl, route('catalog.assortment')) !== false) {
            return $lastUrl;
        }

        return route('catalog.assortment');
    }
}