<?php

namespace FreezyBee\Forms;

use FreezyBee\Forms\Containers\CropperContainer;
use FreezyBee\Forms\Controls\DateTimeInput;
use FreezyBee\Forms\Controls\SoundInput;
use Nette\Application\UI\Form;

/**
 * Class FreezyForm
 * @package App\Controls
 */
class FreezyForm extends Form
{
    /**
     * @param string $name
     * @param string|null $label
     * @param bool $useMinutes
     * @param bool $allowNull
     * @return DateTimeInput
     */
    public function addDateTime($name, $label = null, $useMinutes = false, $allowNull = false)
    {
        if ($label === null) {
            $label = $name;
        }

        $control = new DateTimeInput($label, $useMinutes, $allowNull);
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

        $control = new CropperContainer($label, $label, $params, $name);
        if ($this->currentGroup !== null) {
            $this->currentGroup->add($control['file']);
            $this->currentGroup->add($control['json']);
        }
        return $this[$name] = $control;
    }

    /**
     * @param $name
     * @param bool|false $label
     * @return SoundInput
     */
    public function addSoundUpload($name, $label = false)
    {
        if ($label === false) {
            $label = $name;
        }

        $control = new SoundInput($label);
        return $this[$name] = $control;
    }
}
