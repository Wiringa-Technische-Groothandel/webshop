<?php

declare(strict_types=1);

namespace WTG\RestClient\Model\Rest\GetLastChangeId;

use Illuminate\Support\Facades\DB;
use WTG\RestClient\Api\Model\RequestInterface;
use WTG\RestClient\Model\Rest\AbstractRequest;

/**
 * GetLastChangeId request model.
 *
 * @package     WTG\RestClient
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Request extends AbstractRequest implements RequestInterface
{
    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return sprintf('%s/requestChangedIds', config('wtg.rest.admin_code'));
    }

    /**
     * Request type.
     *
     * @return string
     */
    public function type(): string
    {
        return self::HTTP_REQUEST_TYPE_POST;
    }

    /**
     * @inheritDoc
     */
    public function body(): ?array
    {
        return [
            'changeNumberStart' => 1,
            'limit' => 1,
            'requestNames' => [
                [
                    'requestName' => 'Products'
                ]
            ],
            'propertiesToInclude' => 'changeNumberEnd',
        ];
    }

    /**
     * Get the last chage number.
     *
     * @return int
     */
    protected function getLastChange(): int
    {
        $lastChangeNumber = DB::table('config')
            ->where('key', 'last_product_change_number')
            ->first('value');

        return (int) ($lastChangeNumber->value ?: 1);
    }
}
