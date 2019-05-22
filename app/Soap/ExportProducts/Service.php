<?php

namespace WTG\Soap\ExportProducts;

use Carbon\Carbon;
use WTG\Soap\AbstractService;

/**
 * ExportProducts service.
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
     * Service constructor.
     */
    public function __construct()
    {
        $this->request = app(Request::class);
        $this->response = app(Response::class);
    }

    /**
     * Run the service.
     *
     * @return mixed|Response
     */
    public function handle()
    {
        try {
            $soapResponse = $this->sendRequest(
                "ExportProducts",
                $this->request
            );
            $this->buildResponse($soapResponse);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->response;
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
        dd($soapResponse);

        $this->response->code = 200;
        $this->response->message = 'Success';
    }
}