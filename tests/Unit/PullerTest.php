<?php

namespace Felrov\TranslationSheet\Test\Unit;

use Mockery;
use GuzzleHttp\Subscriber\Mock;
use Felrov\TranslationSheet\Puller;
use Felrov\TranslationSheet\Test\TestCase;
use Felrov\TranslationSheet\Translation\Writer;
use Felrov\TranslationSheet\Sheet\TranslationsSheet;

class PullerTest extends TestCase
{
    /** @test */
    public function it_pulls_the_translations()
    {
        $translationSheet = Mockery::mock(TranslationsSheet::class);
        $translationSheet->shouldReceive('getSpreadsheet')->once()->andReturn($this->helper->spreadsheet());
        $translationSheet->shouldReceive('readTranslations')->once();

        $writer = Mockery::mock(Writer::class);
        $writer->shouldReceive('withOutput')->once()->andReturn($writer);
        $writer->shouldReceive('setTranslations')->once()->andReturn($writer);
        $writer->shouldReceive('write');

        $puller = new Puller($translationSheet, $writer);
        $puller->pull();
    }
}
