<?php

namespace Felrov\TranslationSheet\Test\Unit;

use Mockery;
use Illuminate\Support\Collection;
use Felrov\TranslationSheet\Pusher;
use Felrov\TranslationSheet\Test\TestCase;
use Felrov\TranslationSheet\Translation\Reader;
use Felrov\TranslationSheet\Sheet\TranslationsSheet;
use Felrov\TranslationSheet\Translation\Transformer;

class PusherTest extends TestCase
{
    /** @test */
    public function it_pushes_translations()
    {
        $transformer = Mockery::mock(Transformer::class);
        $transformer->shouldReceive('setLocales')->once()->andReturn($transformer);
        $transformer->shouldReceive('transform')->once()->andReturn(new Collection);

        $reader = Mockery::mock(Reader::class);
        $reader->shouldReceive('scan')->once()->andReturn(new Collection);

        $translationSheet = Mockery::mock(TranslationsSheet::class);
        $translationSheet->shouldReceive('getSpreadsheet')->once()->andReturn($this->helper->spreadsheet());
        $translationSheet->shouldReceive('writeTranslations')->once();
        $translationSheet->shouldReceive('prepareForWrite')->once();
        $translationSheet->shouldReceive('updateHeaderRow')->once();
        $translationSheet->shouldReceive('styleDocument')->once();

        $pusher = new Pusher($reader, $translationSheet, $transformer);
        $pusher->push();
    }
}
