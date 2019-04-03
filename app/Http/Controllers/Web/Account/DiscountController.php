<?php

namespace WTG\Http\Controllers\Web\Account;

use WTG\Models\Customer;
use Illuminate\Http\Request;
use WTG\Http\Controllers\Controller;
use WTG\Services\DiscountFileService;
use WTG\Exceptions\InvalidFormatException;
use WTG\Http\Requests\DownloadDiscountFileRequest;

/**
 * Discount controller.
 *
 * @package     WTG\Http
 * @subpackage  Controllers\Account
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DiscountController extends Controller
{
    const RESPONSE_TYPE_DOWNLOAD = 'download';
    const RESPONSE_TYPE_EMAIL = 'email';

    /**
     * Generator page
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function getAction(Request $request)
    {
        /** @var Customer $customer */
        $customer = $request->user();

        return view('pages.account.discount', compact('customer'));
    }

    public function postAction(DownloadDiscountFileRequest $request, DiscountFileService $service)
    {
        /** @var Customer $customer */
        $customer = $request->user();
        $service->setCustomer($customer);
        $format = $request->input('format');
        $receive = $request->input('receive');

        try {
            $data = $service->run($format);
        } catch (InvalidFormatException $e) {
            return back()->withErrors(__("Ongeldig bestands formaat."));
        }

        if ($receive === self::RESPONSE_TYPE_DOWNLOAD) {
            return response()->make($data, 200, [
                'Content-type'        => 'text/plain',
                'Content-Disposition' => 'attachment; filename="icc_data.txt"',
            ]);
        } elseif ($receive === self::RESPONSE_TYPE_EMAIL) {
            // Email response
        } else {
            return back();
        }
    }

    /**
     * Generate the file
     *
     * @param  Request  $request
     * @param  string  $type
     * @param  string  $method
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function generate(Request $request, $type, $method)
    {
        if ($type === 'icc') {
            $contents = app('discount_file')->icc();
            $filename = 'icc_data'.Auth::user()->company_id.'.txt';
        } elseif ($type === 'csv') {
            $contents = app('discount_file')->csv();
            $filename = 'icc_data'.Auth::user()->company_id.'.csv';
        } else {
            return redirect('account/discountfile')
                ->withErrors('Ongeldig bestands type.');
        }

        $filePath = storage_path('export/discounts/'.$filename);

        if (\File::exists($filePath)) {
            \File::delete($filePath);
        }

        \File::put($filePath, $contents);

        if ($method === 'download') {
            return \Response::download($filePath);
        } elseif ($method === 'mail') {
            \Mail::send('email.discountfile', [], function ($message) use ($filePath, $filename) {
                $message->from('verkoop@wiringa.nl', 'Wiringa Webshop');
                $message->to(Auth::user()->email);
                $message->subject('[WTG Webshop] Kortingsbestand');
                $message->attach($filePath, ['as' => $filename]);
            });

            return redirect()
                ->back()
                ->with('status', 'Het kortingsbestand is verzonden naar '.Auth::user()->email);
        } else {
            return redirect()
                ->back()
                ->withErrors('Geen verzendmethode opgegeven.');
        }
    }
}