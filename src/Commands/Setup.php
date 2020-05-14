<?php

namespace Felrov\TranslationSheet\Commands;

use Illuminate\Console\Command;

class Setup extends Command
{
    protected $signature = 'translation_sheet:setup';

    protected $description = 'Setup spreadsheet and get it ready to host translations';

    public function handle(\Felrov\TranslationSheet\Setup $setup)
    {
        $setup->withOutput($this->output)->run();
    }
}
