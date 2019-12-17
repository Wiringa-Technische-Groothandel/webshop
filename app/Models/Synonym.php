<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Synonym model.
 *
 * @package     WTG\Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Synonym extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return array
     */
    public static function createMapping(): array
    {
        $synonyms = self::all();
        $mapping = [];

        foreach ($synonyms as $synonym) {
            $mapping[] = sprintf('%s => %s', $synonym->getSource(), $synonym->getTarget());
        }

        return $mapping;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->getAttribute('source');
    }

    /**
     * @return string|null
     */
    public function getTarget(): ?string
    {
        return $this->getAttribute('target');
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getAttribute('id');
    }

    /**
     * @param string $source
     * @return Synonym
     */
    public function setSource(string $source): self
    {
        return $this->setAttribute('source', $source);
    }

    /**
     * @param string $target
     * @return Synonym
     */
    public function setTarget(string $target): self
    {
        return $this->setAttribute('target', $target);
    }
}
