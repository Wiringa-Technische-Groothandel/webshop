<?php

namespace WTG\Http\Controllers\Web\Account;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\View\Factory as ViewFactory;
use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use WTG\Http\Requests\DownloadOrderRequest;
use WTG\Services\Import\Invoices as InvoiceService;

/**
 * Invoice controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class InvoiceController extends Controller
{
    /**
     * @var InvoiceService
     */
    protected $service;

    /**
     * InvoiceController constructor.
     *
     * @param  ViewFactory  $view
     * @param  InvoiceService  $invoiceService
     */
    public function __construct(ViewFactory $view, InvoiceService $invoiceService)
    {
        $this->service = $invoiceService;

        parent::__construct($view);
    }

    /**
     * Order history view.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function getAction(Request $request): View
    {
        $invoices = $this->service->getForCustomer(
            $request->user()->getCompany()->getCustomerNumber()
        );

        return view('pages.account.invoices', compact('invoices'));
    }

    /**
     * Order history view.
     *
     * @param  Request  $request
     * @param  int  $file
     * @return Response
     */
    public function viewAction(Request $request, int $file)
    {
        $invoices = $this->service->getForCustomer(
            $request->user()->getCompany()->getCustomerNumber()
        );

        $filenames = $invoices->get('files');
        $filename = $filenames[$file] ?? false;

        if (! $filename) {
            return back()->withErrors(__('De opgevraagde factuur is niet gevonden'));
        }

        try {
            $data = $this->service->readFile($filename);
        } catch (\Exception $e) {
            return back()->withErrors(__('Er is een fout opgetreden tijdens het ophalen van de factuur.'));
        }

        return response()->make($data, 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}