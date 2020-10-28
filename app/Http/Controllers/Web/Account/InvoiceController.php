<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Web\Account;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\Factory as ViewFactory;
use WTG\Http\Controllers\Controller;
use WTG\Managers\InvoiceManager;

/**
 * Invoice controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class InvoiceController extends Controller
{
    protected InvoiceManager $invoiceManager;

    /**
     * InvoiceController constructor.
     *
     * @param ViewFactory $view
     * @param InvoiceManager $invoiceManager
     */
    public function __construct(ViewFactory $view, InvoiceManager $invoiceManager)
    {
        $this->invoiceManager = $invoiceManager;

        parent::__construct($view);
    }

    /**
     * Invoice list.
     *
     * @param Request $request
     * @return View
     */
    public function getAction(Request $request): View
    {
        switch ((int)$request->input('sort-order')) {
            case InvoiceManager::SORT_ORDER_ASC:
            case InvoiceManager::SORT_ORDER_DESC:
                $sortOrder = (int)$request->input('sort-order');
                break;
            default:
                $sortOrder = InvoiceManager::SORT_ORDER_DESC;
        }

        $invoices = $this->invoiceManager->getForCompany(
            $request->user()->getCompany(),
            true,
            $sortOrder
        );

        return view('pages.account.invoices', compact('invoices'));
    }

    /**
     * Invoice view.
     *
     * @param Request $request
     * @param int $file
     * @return Response
     */
    public function viewAction(Request $request, int $file)
    {
        $invoices = $this->invoiceManager->getForCompany(
            $request->user()->getCompany()
        );

        $invoice = $invoices->get($file);

        if (! $invoice) {
            return back()->withErrors(__('De opgevraagde factuur is niet gevonden'));
        }

        $filename = $invoice->get('filename');

        try {
            $data = $this->invoiceManager->readFile($filename);
        } catch (Exception $e) {
            return back()->withErrors(__('Er is een fout opgetreden tijdens het ophalen van de factuur.'));
        }

        return response()->make(
            $data,
            200,
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
}
