<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\SearchTerms;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Throwable;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Search\SearchTermsManager;

/**
 * Admin API save synonyms controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SaveController extends Controller
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
            $this->searchTermsManager->saveTerms(
                $this->request->input('terms', [])
            );
        } catch (Throwable $throwable) {
            return response()->json(
                [
                    'message' => $throwable->getMessage(),
                    'success' => false,
                ]
            );
        }

        return response()->json(
            [
                'searchTerms' => $this->searchTermsManager->getTerms(),
                'message' => __('De zoektermen zijn opgeslagen.'),
                'success' => true,
            ]
        );
    }
}