<?php

namespace FreezyBee\Forms;

use FreezyBee\Forms\Containers\CropperContainer;
use FreezyBee\Forms\Controls\DateTimeInput;
use Nette\Application\UI\Form;

/**
 * Class FreezyForm
 * @package App\Controls
 */
class FreezyForm extends Form
{
    /**
     * @param $name
     * @param bool|false $label
     * @param bool $useMinutes
     * @return DateTimeInput
     */
    public function addDateTime($name, $label = false, $useMinutes = false)
    {
        if ($label === false) {
            $label = $name;
        }

        $control = new DateTimeInput($label, $useMinutes);
        return $this[$name] = $control;
    }

    /**
     * @param $name
     * @param $label
     * @param $params
     * @return CropperContainer
     */
    public function addCropperContainer($name, $label = null, $params = [])
    {
        if (is_null($label)) {
            $label = $name;
        }

        $control = new CropperContainer($label, $label, $params);
        if ($this->currentGroup !== null) {
            $this->currentGroup->add($control['file']);
            $this->currentGroup->add($control['json']);
        }
        return $this[$name] = $control;
    }
}