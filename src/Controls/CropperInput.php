<?php

namespace FreezyBee\Forms\Controls;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\IControl;
use Nette\Utils\Html;

/**
 * Class CropperInput
 * @package FreezyBee\Forms\Controls
 */
class CropperInput extends BaseControl implements IControl
{
    /**
     * @var string
     */
    private $params;

    public function __construct($caption, $params)
    {
        $this->params = $params;
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

        $el->add($textInput->addAttributes(['hidden' => 'hidden', 'class' => 'netteCropperJson']));

        if (!empty($this->params['src'])) {
            $image = Html::el('img')->addAttributes([
                'src' => $this->params['src'],
                'class' => 'netteCropperOldPreview',
                'style' => 'max-width: 90%'
            ]);
            $el->add($image);
        }

        return $el;
    }
}
