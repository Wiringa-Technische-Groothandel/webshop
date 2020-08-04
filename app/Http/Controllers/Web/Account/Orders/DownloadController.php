<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account\Orders;

use Illuminate\Http\Request;
use Illuminate\View\Factory as ViewFactory;
use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Web\Controller;
use WTG\Models\Customer;
use WTG\Models\Order;

class DownloadController extends Controller
{
    protected Request $request;

    /**
     * DownloadController constructor.
     *
     * @param ViewFactory $view
     * @param Request $request
     */
    public function __construct(ViewFactory $view, Request $request)
    {
        parent::__construct($view);

        $this->request = $request;
    }

    public function execute(): Response
    {
        /** @var Customer $customer */
        $customer = $this->request->user();

        /** @var Order $order */
        $order = $customer
            ->getCompany()
            ->orders()
            ->where('uuid', $this->request->route('uuid'))
            ->first();

        if (! $order) {
            return back()->withErrors(__("Er was geen order gevonden met het opgegeven id."));
        }

        $pdfData = $order->toPdf();

        if (! $pdfData) {
            return back()->withErrors(__("Er is een fout opgetreden tijdens het genereren van de PDF."));
        }

        return response(
            $pdfData,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="order_' . $order->getAttribute('created_at')->format(
                        'YmdHi'
                    ) . '.pdf"',
            ]
        );
    }
}
