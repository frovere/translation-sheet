<?php

namespace Nikaia\TranslationSheet\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Nikaia\TranslationSheet\Commands\Output;
use Nikaia\TranslationSheet\Spreadsheet;
use Nikaia\TranslationSheet\Util;

class Writer
{
    use Output;

    /** @var Collection */
    protected $translations;

    /** @var Spreadsheet */
    protected $spreadsheet;

    /** @var Filesystem */
    protected $files;

    /** @var Application */
    protected $app;

    public function __construct(Spreadsheet $spreadsheet, Filesystem $files, Application $app)
    {
        $this->spreadsheet = $spreadsheet;
        $this->files = $files;
        $this->app = $app;

        $this->nullOutput();
    }

    public function setTranslations($translations)
    {
        $this->translations = $translations;

        return $this;
    }

    public function write()
    {
        $this
            ->groupTranslationsByFile()
            ->each(function ($items, $sourceFile) {
                if ($this->files->extension($sourceFile) == 'json') {
                    return $this->writeJsonFile($this->app->make('path.lang').'/'.$sourceFile, $items);
                }

                $this->writeFile(
                    $this->app->make('path.lang').'/'.$sourceFile,
                    $items
                );
            });
    }

    protected function writeFile($file, $items)
    {
        $this->output->writeln('  '.$file);

        $content = "<?php\n\nreturn ".Util::varExport($items).";\n";

        if (! $this->files->isDirectory($dir = dirname($file))) {
            $this->files->makeDirectory($dir, 0755, true);
        }

        $this->files->put($file, $content);
    }

    protected function writeJsonFile($file, $items)
    {
        $this->output->writeln('  JSON: '.$file);

        if (! $this->files->isDirectory($dir = dirname($file))) {
            $this->files->makeDirectory($dir, 0755, true);
        }

        $this->files->put($file, json_encode($items, JSON_PRETTY_PRINT));
    }

    protected function groupTranslationsByFile()
    {
        $items = $this
            ->translations
            ->groupBy('sourceFile')
            ->map(function ($fileTranslations) {
                return $this->buildTranslationsForFile($fileTranslations);
            });

        // flatten does not seem to work for every case. !!! refactor !!!
        $result = [];
        foreach ($items as $subitems) {
            $result = array_merge($result, $subitems);
        }

        return new Collection($result);
    }

    protected function buildTranslationsForFile($fileTranslations)
    {
        $files = [];
        $locales = $this->spreadsheet->getLocales();

        foreach ($locales as $locale) {
            foreach ($fileTranslations as $translation) {

                // We will only write non empty translations
                // For instance, we have `app.title` that is the same for each locale,
                // We dont want to translate it to every locale, and prefer letting
                // Laravel default back to the default locale.
                if ($this->skipToDefault($translation, $locale)) {
                    continue;
                }

                $localeFile = $translation['sourceFile'] == '{locale}.json' ?
                    $locale . '.json' :
                    str_replace('{locale}/', $locale . '/', $translation['sourceFile']);

                if (empty($files[$localeFile])) {
                    $files[$localeFile] = [];
                }

                Arr::set($files[$localeFile], $translation['key'], $translation[$locale]);
            }
        }

        return $files;
    }

    private function skipToDefault($translation, $locale)
    {
        if (! isset($translation[$locale])) {
            return true;
        }

        return empty($translation[$locale]) && $translation['sourceFile'] == '{locale}.json';
    }
}
