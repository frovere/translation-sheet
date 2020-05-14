<?php

namespace Felrov\TranslationSheet\Test\Unit;

use Mockery;
use Felrov\TranslationSheet\Setup;
use Felrov\TranslationSheet\Test\TestCase;
use Felrov\TranslationSheet\Sheet\MetaSheet;
use Felrov\TranslationSheet\Sheet\TranslationsSheet;

class SetupTest extends TestCase
{
    /** @test */
    public function it_setup_the_spreadsheet()
    {
        $translationSheet = Mockery::mock(TranslationsSheet::class);
        $translationSheet->shouldReceive('setup')->once();

        $metaSheet = Mockery::mock(MetaSheet::class);
        $metaSheet->shouldReceive('setup')->once();

        $setup = new Setup($translationSheet, $metaSheet);
        $setup->run();
    }
}
