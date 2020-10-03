<?php

namespace FreezyBee\Forms\Controls;

use FreezyBee\Forms\Utils\FileSizeElement;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

class CropperInput extends BaseControl
{
    /** @var array */
    private $params;

    /** @var string */
    private $containerName;

    public function __construct(string $caption, array $params, string $containerName)
    {
        $this->params = $params;
        $this->containerName = $containerName;
        parent::__construct($caption);
    }

    /**
     * @return mixed
     */
    public function getControl()
    {
        $el = Html::el();

        /** @var Html $textInput */
        $textInput = parent::getControl();

        $el->addHtml($textInput->addAttributes([
            'hidden' => 'hidden',
            'class' => 'netteCropperJson',
            'data-nette-cropper-name' => $this->containerName
        ]));

        if (!empty($this->params['src'])) {
            if (!empty($this->params['wwwDir'])) {
                $el->addHtml(new FileSizeElement($this->params['wwwDir'] . $this->params['src'], $this->params['src']));
            }

            $image = Html::el('img')->addAttributes([
                'src' => $this->params['src'] . '?v=' . date('YmdHi'),
                'class' => 'netteCropperOldPreview',
                'data-nette-cropper-name' => $this->containerName,
                'style' => 'max-width: 90%'
            ]);
            $el->addHtml($image);
        }

        return $el;
    }
}
