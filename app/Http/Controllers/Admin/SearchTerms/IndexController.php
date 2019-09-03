<?php declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\SearchTerms;

use Illuminate\Contracts\View\View;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Synonym;

/**
 * Admin search terms overview controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @return View
     */
    public function getAction(): View
    {
        $synonyms = Synonym::all();

        return $this->view->make('pages.admin.search-terms', [
            'synonyms' => $synonyms
        ]);
    }
}