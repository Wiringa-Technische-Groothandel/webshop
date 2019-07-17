<?php

namespace WTG\Providers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder as ElasticBuilder;

use Illuminate\Support\ServiceProvider;

use Laravel\Scout\EngineManager;

use ScoutEngines\Elasticsearch\ElasticsearchEngine;

/**
 * Elasticsearch service provider.
 *
 * @package     WTG\Providers
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ElasticsearchProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->bind(Client::class, function ($app) {
            return ElasticBuilder::create()
                ->setHosts(config('scout.elasticsearch.hosts'))
                ->build();
        });

        /** @var EngineManager $engineManager */
        $engineManager = $this->app[EngineManager::class];
        $engineManager->extend('elasticsearch', function ($app) {
            return new ElasticsearchEngine(
                $app[Client::class], config('scout.elasticsearch.index')
            );
        });
    }
}
