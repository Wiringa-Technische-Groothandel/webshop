<?php

declare(strict_types=1);

namespace WTG\Soap\GetOrderCount;

use Carbon\Carbon;
use WTG\Soap\AbstractService;

/**
 * GetOrderCount service.
 *
 * @package     WTG\Soap
 * @subpackage  GetOrderCount
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Service extends AbstractService
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var string
     */
    protected $customerId;

    /**
     * @var Carbon
     */
    protected $from;

    /**
     * @var Carbon
     */
    protected $to;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->request = app()->make(Request::class);
        $this->response = app()->make(Response::class);
    }

    /**
     * Run the service.
     *
     * @param  string  $customerId
     * @param  Carbon|null  $from
     * @param  Carbon|null  $to
     * @return mixed|Response
     */
    public function handle(string $customerId, ?Carbon $from = null, ?Carbon $to = null)
    {
        $this->customerId = $customerId;
        $this->from = $from ?: Carbon::create(2018, 1, 1); // There is no order history from before 2018
        $this->to = $to ?: Carbon::now();

        try {
            $this->buildRequest();
            $soapResponse = $this->sendRequest(
                "GetSalesOrderHeaderCount",
                $this->request
            );
            $this->buildResponse($soapResponse);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->response;
    }

    /**
     * Build the request.
     *
     * @return void
     */
    protected function buildRequest()
    {
        $this->request->customerId = $this->customerId;
        $this->request->orderDateFrom = $this->from->format('Y-m-d');
        $this->request->orderDateTo = $this->to->format('Y-m-d');
    }

    /**
     * Build the response.
     *
     * @param  object  $soapResponse
     * @return void
     * @throws Exception
     */
    protected function buildResponse($soapResponse)
    {
        $count = (int) $soapResponse->OrderCount;

        $this->response->count = $count ?? 0;
        $this->response->code = 200;
        $this->response->message = 'Success';
    }
}