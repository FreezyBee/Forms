<?php

namespace FreezyBee\Forms\Controls;

use Nette\Forms\Controls\UploadControl;
use Nette\Utils\Html;

/**
 * Class CropperInput
 * @package FreezyBee\Forms\Controls
 */
class SoundInput extends UploadControl
{
    /**
     * @var string
     */
    private $params;

    /**
     * @var string
     */
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
        $add = (method_exists('Nette\Utils\Html', 'addHtml')) ? 'addHtml' : 'add';

        if ($this->filePath) {
            return Html::el()->$add(parent::getControl()->setAttribute('accept', '.mp3'))
                ->$add(Html::el()->setHtml('<br><audio controls><source src="' . $this->filePath . '" type="audio/mpeg">
                    Your browser does not support the audio element.</audio>'));

        } else {
            return parent::getControl();
        }
    }

    /**
     * @param $value
     * @return static
     */
    public function setValue($value)
    {
        $this->filePath = $value;
        return parent::setValue($value);
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
}
