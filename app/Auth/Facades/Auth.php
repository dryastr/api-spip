<?php

namespace App\Auth\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Elastic\Elasticsearch\Response\Elasticsearch|\GuzzleHttp\Promise\Promise index()
 * @method static \Elastic\Elasticsearch\Response\Elasticsearch|\GuzzleHttp\Promise\Promise update()
 * @method static \Elastic\Elasticsearch\Response\Elasticsearch|\GuzzleHttp\Promise\Promise search()
 * @method static \Elastic\Elasticsearch\Response\Elasticsearch|\GuzzleHttp\Promise\Promise match()
 * @method static ?array getLog(mixed $index, array $query = null, array $sort = null, ?int $page = null, ?int $per_page = null)
 * @method static ?array getPaginatedLog(mixed $index, ?array $query = null, ?array $sort = null, bool $pagination = true)
 * @method static mixed storeLog(string $index, array $body)
 *
 * @see MifxPackage\Auth\Auth;
 */
class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'spip.auth';
    }
}
