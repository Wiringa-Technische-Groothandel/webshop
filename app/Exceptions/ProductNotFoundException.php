<?php

declare(strict_types=1);

namespace WTG\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Product not found exception.
 *
 * @package     WTG\Exceptions
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ProductNotFoundException extends ModelNotFoundException
{
    //
}