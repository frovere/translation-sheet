<?php

namespace Felrov\TranslationSheet;

use Illuminate\Support\ServiceProvider;
use Felrov\TranslationSheet\Client\Client;
use Felrov\TranslationSheet\Commands\Lock;
use Felrov\TranslationSheet\Commands\Open;
use Felrov\TranslationSheet\Commands\Prepare;
use Felrov\TranslationSheet\Commands\Pull;
use Felrov\TranslationSheet\Commands\Push;
use Felrov\TranslationSheet\Commands\Setup;
use Felrov\TranslationSheet\Commands\Status;
use Felrov\TranslationSheet\Commands\Unlock;

class TranslationSheetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('translation_sheet.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'translation_sheet');

        $this->registerGoogleApiClient();

        $this->registerSpreadsheet();

        $this->registerCommands();
    }

    private function registerGoogleApiClient()
    {
        $this->app->singleton(Client::class, function () {
            return Client::create(
                $this->app['config']['translation_sheet.serviceAccountCredentialsFile'],
                $this->app['config']['translation_sheet.googleApplicationName']
            );
        });
    }

    private function registerSpreadsheet()
    {
        $this->app->singleton(Spreadsheet::class, function () {
            return new Spreadsheet(
                $this->app['config']['translation_sheet.spreadsheetId'],
                Util::asArray($this->app['config']['translation_sheet.locales'])
            );
        });
    }

    private function registerCommands()
    {
        $this->commands([
            Setup::class,
            Push::class,
            Pull::class,
            Prepare::class,
            Lock::class,
            Unlock::class,
            Status::class,
            Open::class,
        ]);
    }
}
