<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Api\Catalog;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use WTG\Catalog\PriceManager;
use WTG\Contracts\Models\CustomerContract;
use WTG\Http\Controllers\Api\Controller;

/**
 * Product price api controller.
 *
 * @package     WTG\Http\Controllers\Api\Catalog
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class PriceController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var PriceManager
     */
    protected PriceManager $priceManager;

    /**
     * PriceController constructor.
     *
     * @param Request $request
     * @param PriceManager $priceManager
     */
    public function __construct(Request $request, PriceManager $priceManager)
    {
        $this->request = $request;
        $this->priceManager = $priceManager;
    }

    /**
     * @return Response
     * @throws GuzzleException
     * @throws BindingResolutionException
     */
    public function execute(): Response
    {
        $sku = (string)$this->request->input('sku');
        $qty = (float)$this->request->input('qty', 1);

        if (empty($sku)) {
            return response()->json(
                [
                    'error' => 'Parameter "sku" missing'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        /** @var CustomerContract $customer */
        $customer = $this->request->user();
        $customerNumber = '99999'; //$customer->getCompany()->getCustomerNumber();

        return response()->json(
            $this->priceManager->fetchPrice($customerNumber, $sku, $qty)
        );
    }
}
