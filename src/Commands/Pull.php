<?php

namespace Felrov\TranslationSheet\Commands;

use Illuminate\Console\Command;
use Felrov\TranslationSheet\Puller;

class Pull extends Command
{
    protected $signature = 'translation_sheet:pull';

    protected $description = 'Pull translations from spreadsheet and override local languages files';

    public function handle(Puller $puller)
    {
        $puller->withOutput($this->output)->pull();
    }
}
