<?php

namespace Felrov\TranslationSheet\Commands;

use Illuminate\Console\Command;
use Felrov\TranslationSheet\Pusher;
use Felrov\TranslationSheet\Spreadsheet;
use Felrov\TranslationSheet\Translation\Writer;
use Felrov\TranslationSheet\Util;

class Prepare extends Command
{
    protected $signature = 'translation_sheet:prepare';

    protected $description = 'Rewrite locales languages files by removing comments and sorting keys. This most likely reduce and simplify conflicts when pulling translations from spreadsheet.';

    public function handle(Pusher $pusher, Spreadsheet $spreadsheet, Writer $writer)
    {
        $this->output->writeln('<comment>Scanning local languages files</comment>');
        $translations = Util::keyValues(
            $pusher->getScannedAndTransformedTranslations(),
            $spreadsheet->getCamelizedHeader()
        );

        $this->output->writeln('<comment>Rewriting :</comment>');
        $writer->withOutput($this->output)->setTranslations($translations)->write();

        $this->output->writeln('<info>Done.</info>');
    }
}
