<?php

namespace WTG\Providers;

use WTG\Models\Block;
use League\Flysystem\Filesystem;
use WTG\Soap\Service as SoapService;
use League\Flysystem\Sftp\SftpAdapter;
use Illuminate\Support\ServiceProvider;
use WTG\Contracts\Models\BlockContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('sftp', function ($app, $config) {
            $adapter = new SftpAdapter($config);

            return new Filesystem($adapter);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BlockContract::class, Block::class);

        $this->app->singleton('soap', SoapService::class);

        $this->app->when(SoapService::class)
            ->needs(\SoapClient::class)
            ->give(function () {
                return new \SoapClient(config('soap.wsdl'), [
                    'exceptions' => false
                ]);
            });
    }
}
