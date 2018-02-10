<?php

namespace WTG\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Import data model.
 *
 * @package     WTG
 * @subpackage  Models
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ImportData extends Model
{
    const KEY_LAST_ASSORTMENT_FILE = 'last_assortment_file';
    const KEY_LAST_ASSORTMENT_RUN_TIME = 'last_assortment_run_time';

    /**
     * @var string
     */
    protected $table = 'import_data';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Key scope.
     *
     * @param  Builder  $builder
     * @param  string  $key
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