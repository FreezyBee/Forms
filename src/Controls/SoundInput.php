<?php

namespace FreezyBee\Forms\Controls;

use FreezyBee\Forms\Utils\FileSizeElement;
use Nette\Forms\Controls\UploadControl;
use Nette\Utils\Html;

/**
 * Class CropperInput
 * @package FreezyBee\Forms\Controls
 */
class SoundInput extends UploadControl
{
    /** @var string */
    private $filePath;

    /**
     * SoundInput constructor.
     * @param string $label
     */
    public function __construct($label = null)
    {
        parent::__construct($label);
    }

    /**
     * @return mixed
     */
    public function getControl()
    {
        $control = parent::getControl()->setAttribute('accept', '.mp3');

        if ($this->filePath) {
            $el = Html::el();

            $el->addHtml($control)
                ->addHtml(Html::el()->setHtml('<br><audio controls><source src="' . $this->filePath . '" type="audio/mpeg">
                    Your browser does not support the audio element.</audio>'));

            $el->addHtml(new FileSizeElement($this->filePath));

            return $el;
        }

        return $control;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultValue($value)
    {
        $this->filePath = $value;
        return parent::setDefaultValue($value);
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
}
