<?php

declare(strict_types=1);

namespace WTG\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Import data model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImportData extends Model
{
    public const KEY_LAST_ASSORTMENT_FILE = 'last_assortment_file';
    public const KEY_LAST_ASSORTMENT_RUN_TIME = 'last_assortment_run_time';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'import_data';

    /**
     * Key scope.
     *
     * @param Builder $builder
     * @param string $key
     * @return Builder
     */
    public function scopeKey(Builder $builder, string $key)
    {
        return $builder->where('key', $key);
    }

    /**
     * Get the value.
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->getAttribute('value');
    }
}
