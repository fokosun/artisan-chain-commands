<?php

namespace Fokosun\ArtisanChainCommands\Providers;

use Fokosun\ArtisanChainCommands\Console\ChainCommands;
use Illuminate\Support\ServiceProvider;

class ArtisanChainCommandsProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ChainCommands::class
            ]);
        }
    }
}
