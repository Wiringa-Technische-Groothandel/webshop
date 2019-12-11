<?php

declare(strict_types=1);

namespace WTG\Search;

use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
use Throwable;
use WTG\Models\Synonym;

/**
 * Search terms manager.
 *
 * @package     WTG\Search
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class SearchTermsManager
{
    /**
     * @var DatabaseManager
     */
    protected DatabaseManager $databaseManager;

    /**
     * SearchTermsManager constructor.
     *
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Get all terms.
     *
     * @return Collection
     */
    public function getTerms(): Collection
    {
        return Synonym::all();
    }

    /**
     * Find a search term by id.
     *
     * @param int $id
     * @return Synonym
     */
    public function findTerm(int $id): Synonym
    {
        /** @var Synonym $term */
        $term = Synonym::query()->findOrFail($id);

        return $term;
    }

    /**
     * Create a new search term.
     *
     * @param string $source
     * @param string $target
     * @return Synonym
     */
    public function createTerm(string $source, string $target): Synonym
    {
        $synonym = new Synonym();
        $synonym->setSource($source);
        $synonym->setTarget($target);
        $synonym->save();

        return $synonym;
    }

    /**
     * @param array $terms
     * @throws Throwable
     */
    public function saveTerms(array $terms): void
    {
        try {
            $this->databaseManager->beginTransaction();

            foreach ($terms as $term) {
                if (! $term['id']) {
                    if (empty($term['source']) || empty($term['target'])) {
                        continue;
                    }

                    $this->createTerm($term['source'], $term['target']);
                } else {
                    /** @var Synonym $synonym */
                    $synonym = $this->findTerm($term['id']);

                    if (empty($term['source']) || empty($term['target'])) {
                        if ($synonym) {
                            $synonym->delete();
                        }

                        continue;
                    }

                    if (
                        $term['source'] === $synonym->getSource() &&
                        $term['target'] === $synonym->getTarget()
                    ) {
                        continue;
                    }

                    $synonym->setSource($term['source']);
                    $synonym->setTarget($term['target']);
                    $synonym->save();
                }
            }

            $this->databaseManager->commit();
        } catch (Throwable $throwable) {
            $this->databaseManager->rollBack();

            throw $throwable;
        }
    }

    /**
     * @param Synonym $term
     * @throws Throwable
     */
    public function deleteTerm(Synonym $term): void
    {
        try {
            $this->databaseManager->beginTransaction();

            $term->delete();

            $this->databaseManager->commit();
        } catch (Throwable $throwable) {
            $this->databaseManager->rollBack();

            throw $throwable;
        }
    }
}