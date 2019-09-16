<?php declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\SearchTerms;

use Exception;

use Illuminate\Http\JsonResponse;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Synonym;

/**
 * Admin search terms delete controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class DeleteController extends Controller
{
    /**
     * @param string $id
     * @return JsonResponse
     * @throws Exception
     */
    public function deleteAction(string $id): JsonResponse
    {
        /** @var Synonym $synonym */
        $synonym = Synonym::findOrFail($id);
        $synonym->delete();

        return response()->json([
            'message' => __('De zoekterm is verwijderd'),
            'success' => true
        ]);
    }
}