<?php

namespace FreezyBee\Forms;

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
}