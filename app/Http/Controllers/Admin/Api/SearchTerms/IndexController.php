<?php

declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\Api\SearchTerms;

use Symfony\Component\HttpFoundation\Response;
use WTG\Http\Controllers\Admin\Controller;
use WTG\Managers\SearchTermsManager;

/**
 * Admin API synonym index controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @var SearchTermsManager
     */
    protected SearchTermsManager $searchTermsManager;

    /**
     * IndexController constructor.
     *
     * @param SearchTermsManager $searchTermsManager
     */
    public function __construct(SearchTermsManager $searchTermsManager)
    {
        $this->searchTermsManager = $searchTermsManager;
    }

    /**
     * @return Response
     */
    public function execute(): Response
    {
        return response()->json(
            [
                'searchTerms' => $this->searchTermsManager->getTerms(),
            ]
        );
    }
}
