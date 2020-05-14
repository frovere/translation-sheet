<?php

namespace Felrov\TranslationSheet\Commands;

use Illuminate\Console\Command;
use Felrov\TranslationSheet\Sheet\TranslationsSheet;

class Lock extends Command
{
    protected $signature = 'translation_sheet:lock';

    protected $description = 'Lock the spreadsheet translations area, to prevent changes.';

    public function handle(TranslationsSheet $translationsSheet)
    {
        $this->comment('Locking the translations spreadsheet.');

        $translationsSheet->lockTranslations();

        $this->info('Done.');
    }
}
