<?php declare(strict_types=1);

namespace WTG\Http\Controllers\Admin\SearchTerms;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use WTG\Http\Controllers\Admin\Controller;
use WTG\Models\Synonym;

/**
 * Admin search terms save controller.
 *
 * @package     WTG\Http
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class SaveController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function postAction(Request $request): RedirectResponse
    {
        $created = $updated = $deleted = 0;
        $oldTerms = $request->input('terms', []);

        foreach ($oldTerms as $id => $oldTerm) {
            /** @var Synonym $synonym */
            $synonym = Synonym::find($id);

            if (empty($oldTerm['source']) || empty($oldTerm['target'])) {
                if ($synonym) {
                    $synonym->delete();
                    $deleted++;
                }

                continue;
            }

            if (
                $oldTerm['source'] === $synonym->getSource() &&
                $oldTerm['target'] === $synonym->getTarget()
            ) {
                continue;
            }

            $synonym->setSource($oldTerm['source']);
            $synonym->setTarget($oldTerm['target']);
            $synonym->save();

            $updated++;
        }

        $newTerms = $request->input('new-terms');

        foreach ($newTerms as $id => $newTerm) {
            if (empty($newTerm['source']) || empty($newTerm['target'])) {
                continue;
            }

            $synonym = new Synonym;
            $synonym->setSource($newTerm['source']);
            $synonym->setTarget($newTerm['target']);
            $synonym->save();

            $created++;
        }

        return back()->with('status', __('Er is/zijn :created zoekterm(en) aangemaakt, :updated aangepast en :deleted verwijderd.', [
            'created' => $created,
            'updated' => $updated,
            'deleted' => $deleted
        ]));
    }
}