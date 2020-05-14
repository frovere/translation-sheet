<?php

namespace Felrov\TranslationSheet;

use Felrov\TranslationSheet\Commands\Output;
use Felrov\TranslationSheet\Sheet\TranslationsSheet;
use Felrov\TranslationSheet\Translation\Writer;

class Puller
{
    use Output;

    /** @var TranslationsSheet */
    protected $translationsSheet;

    /** @var Writer */
    protected $writer;

    public function __construct(TranslationsSheet $translationsSheet, Writer $writer)
    {
        $this->translationsSheet = $translationsSheet;
        $this->writer = $writer;

        $this->nullOutput();
    }

    public function pull()
    {
        $this->output->writeln('<comment>Pulling translation from Spreadsheet</comment>');
        $translations = $this->getTranslations();

        $this->output->writeln('<comment>Writing languages files :</comment>');
        $this->writer
            ->withOutput($this->output)
            ->setTranslations($translations)
            ->write();

        $this->output->writeln('<info>Done.</info>');
    }

    public function getTranslations()
    {
        $header = $this->translationsSheet->getSpreadsheet()->getCamelizedHeader();

        $translations = $this->translationsSheet->readTranslations();

        return Util::keyValues($translations, $header);
    }
}
