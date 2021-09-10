<?php

namespace FreezyBee\Forms\Utils;

use Latte\Runtime\Filters;
use Nette\Utils\Html;

/**
 * Class FileSizeElement
 * @package FreezyBee\Forms\Utils
 */
class FileSizeElement extends Html
{
    /**
     * FileSizeElement constructor.
     * @param string $filename
     * @param string $src
     */
    public function __construct($filename, $src = '')
    {
        $size = $this->getFilesize($filename);

        if ($size) {
            $this->addHtml(
                Html::el('div')
                    ->addText('Nahraný soubor: ')
                    ->addHtml(
                        Html::el('a download style="font-weight: bold"')
                            ->href($src ?: $filename)->addText(basename($filename))
                    )
                    ->addText(' (' . Filters::bytes($size) . ')')
                    ->addHtml('<hr>')
            );
        }
    }

    private function getFilesize(string $filename): int
    {
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            $ch = curl_init($filename);
            if ($ch === false) {
                return 0;
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);

            $data = curl_exec($ch);
            $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

            curl_close($ch);
            return (int) $size;
        }

        if (file_exists($filename)) {
            return (int) filesize($filename);
        }

        return 0;
    }
}
