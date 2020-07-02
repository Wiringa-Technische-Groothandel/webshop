<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetChangedProducts;

use Illuminate\Support\Collection;
use WTG\RestClient\Model\Rest\AbstractResponse;

/**
 * GetChangedProducts response model.
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
        $results = collect($this->responseData['resultData'] ?? []);

        $this->changeNumberEnd = $this->responseData['changeNumberEnd'] ?? 1;
        $this->data = $results->map(
            function ($result) {
                return [
                    'erp_id'        => $result['id'],
                    'action'        => $result['action'],
                    'change_number' => $result['changeNumber']
                ];
            }
        );
    }
}
