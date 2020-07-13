<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetLastChangeId;

use Illuminate\Support\Collection;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetLastChangeId response model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class Response extends AbstractResponse
{
    public Collection $data;
    public int $changeNumberEnd;

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $this->changeNumberEnd = $this->responseData['changeNumberEnd'] ?? 1;
    }
}
