<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account\Orders;

use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Web\Controller;
use WTG\Managers\AuthManager;
use WTG\Managers\RestManager;
use WTG\RestClient\Model\Rest\GetOrders\Request as GetOrdersRequest;
use WTG\RestClient\Model\Rest\GetOrders\Response as GetOrdersResponse;

class IndexController extends Controller
{
    protected RestManager $restManager;
    protected AuthManager $authManager;

    /**
     * HistoryController constructor.
     *
     * @param AuthManager $authManager
     * @param RestManager $restManager
     */
    public function __construct(AuthManager $authManager, RestManager $restManager)
    {
        $this->restManager = $restManager;
        $this->authManager = $authManager;
    }

    public function execute(): Response
    {
        $customer = $this->authManager->getCurrentCustomer();

        $filter = sprintf("DebtorCode EQ '%s'", 10013);//$customer->getCompany()->getCustomerNumber());

        $request = new GetOrdersRequest(
            0,
            10,
            $filter,
        );

        /** @var GetOrdersResponse $response */
        $response = $this->restManager->handle($request);

        dd($response->orders);
    }
}
