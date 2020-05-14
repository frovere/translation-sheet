<?php

namespace Felrov\TranslationSheet\Commands;

use Illuminate\Console\Command;
use Felrov\TranslationSheet\Sheet\TranslationsSheet;

class Unlock extends Command
{
    protected $signature = 'translation_sheet:unlock';

    protected $description = 'Unlock the spreadsheet translations area.';

    public function handle(TranslationsSheet $translationsSheet)
    {
        $this->comment('Unlocking the translations spreadsheet.');

        $translationsSheet->unlockTranslations();

        $this->info('<info>Done.</info>');
    }
}
