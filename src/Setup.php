<?php

namespace Felrov\TranslationSheet;

use Felrov\TranslationSheet\Commands\Output;
use Felrov\TranslationSheet\Sheet\MetaSheet;
use Felrov\TranslationSheet\Sheet\TranslationsSheet;

class Setup
{
    use Output;

    /** @var TranslationsSheet */
    protected $translationsSheet;

    /** @var MetaSheet */
    protected $metaSheet;

    public function __construct(TranslationsSheet $translationsSheet, MetaSheet $metaSheet)
    {
        $this->translationsSheet = $translationsSheet;
        $this->metaSheet = $metaSheet;

        $this->nullOutput();
    }

    public function run()
    {
        $this->output->writeln('<comment>Setting up Translations sheet</comment>');
        $this->translationsSheet->setup();

        $this->output->writeln('<comment>Adding Meta sheet</comment>');
        $this->metaSheet->setup();

        $this->output->writeln('<info>Done. Spreasheet is ready.</info>');
    }
}
