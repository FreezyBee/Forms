<?php

namespace FreezyBee\Forms\Rendering;

use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\Forms\Controls;
use Nette\Forms\Form;
use Nette;
use Nette\Utils\Html;

/**
 * FormRenderer for Bootstrap 4 framework.
 */
class Bs4FormRenderer extends DefaultFormRenderer
{
    /** @var Controls\Button */
    public $primaryButton = NULL;

    /** @var bool */
    private $controlsInit = FALSE;

    /** @var bool */
    private $horizontal = TRUE;

    /** @var bool */
    private $ownButtons = FALSE;

    /**
     * @param string $label label div class
     * @param string $control control div class
     * @param bool|TRUE $horizontal use horizontal form class
     * @param bool|FALSE $ownButtons use own buttons - disable default buttons style
     */
    public function __construct($label = "col-sm-3", $control = "col-sm-9", $horizontal = TRUE, $ownButtons = FALSE)
    {
        $this->wrappers['controls']['container'] = NULL;
        $this->wrappers['pair']['container'] = 'fieldset class=form-group';
        $this->wrappers['pair']['.error'] = 'has-error';
        $this->wrappers['control']['container'] = 'div class="' . $control . '"';
        $this->wrappers['label']['container'] = 'div class="' . $label . ' control-label"';
        $this->wrappers['control']['description'] = 'span class=help-block';
        $this->wrappers['control']['errorcontainer'] = 'span class=help-block';

        $this->horizontal = $horizontal;
        $this->ownButtons = $ownButtons;
    }


    public function renderBegin()
    {
        $this->controlsInit();
        return parent::renderBegin();
    }


    public function renderEnd()
    {
        $this->controlsInit();
        return parent::renderEnd();
    }


    public function renderBody()
    {
        $this->controlsInit();
        return parent::renderBody();
    }


    public function renderControls($parent)
    {
        $this->controlsInit();
        return parent::renderControls($parent);
    }


    public function renderPair(Nette\Forms\IControl $control)
    {
        $this->controlsInit();
        return parent::renderPair($control);
    }


    public function renderPairMulti(array $controls)
    {
        $this->controlsInit();
        return parent::renderPairMulti($controls);
    }


    public function renderLabel(Nette\Forms\IControl $control)
    {
        $this->controlsInit();
        return parent::renderLabel($control);
    }


    public function renderControl(Nette\Forms\IControl $control)
    {
        $this->controlsInit();
        return parent::renderControl($control);
    }


    private function controlsInit()
    {
        if ($this->controlsInit) {
            return;
        }

        $this->controlsInit = TRUE;

        if ($this->horizontal) {
            $this->form->getElementPrototype()->addClass('form-horizontal');
        }

        foreach ($this->form->getControls() as $control) {
            if ($control instanceof Controls\Button) {
                if (!$this->ownButtons) {

                    $markAsPrimary = $control === $this->primaryButton || (!isset($this->primary) && empty($usedPrimary) && $control->parent instanceof Form);
                    if ($markAsPrimary) {
                        $class = 'btn btn-primary-outline';
                        $usedPrimary = TRUE;
                    } else {
                        $class = 'btn btn-default-outline';
                    }
                    $control->getControlPrototype()->addClass($class);
                }
            } elseif ($control instanceof Controls\TextBase || $control instanceof Controls\SelectBox || $control instanceof Controls\MultiSelectBox) {
                $control->getControlPrototype()->addClass('form-control');

            } elseif ($control instanceof Controls\Checkbox || $control instanceof Controls\CheckboxList || $control instanceof Controls\RadioList) {
                $control->getSeparatorPrototype()->setName('div')->addClass($control->getControlPrototype()->type);
            }
        }

    }

}