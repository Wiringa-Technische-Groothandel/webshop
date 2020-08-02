<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\SearchTerms;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\SearchTermsManager;

/**
 * Admin API synonym delete controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DeleteController extends Controller
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * @var SearchTermsManager
     */
    protected SearchTermsManager $searchTermsManager;

    /**
     * DeleteController constructor.
     *
     * @param Request $request
     * @param SearchTermsManager $searchTermsManager
     */
    public function __construct(Request $request, SearchTermsManager $searchTermsManager)
    {
        $this->request = $request;
        $this->searchTermsManager = $searchTermsManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        try {
            $term = $this->searchTermsManager->findTerm(
                (int)$this->request->input('id')
            );

            $this->searchTermsManager->deleteTerm($term);
        } catch (Throwable $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'searchTerms' => $this->searchTermsManager->getTerms(),
                'message'     => __('De zoekterm is verwijderd.'),
                'success'     => true,
            ]
        );
    }
}
