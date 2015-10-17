<?php

namespace FreezyBee\Forms\Controls;

use Nette;


/**
 * Class DateTimeInput
 * @package FreezyBee\Forms\Controls
 */
class DateTimeInput extends Nette\Forms\Controls\TextBase implements Nette\Forms\IControl
{
    /** @var \DateTimeInterface */
    private $date;

    /** @var bool */
    private $useMinutes;

    /**
     * @param $caption
     * @param bool|false $useMinutes
     */
    public function __construct($caption, $useMinutes = false)
    {
        parent::__construct($caption);
        $this->useMinutes = $useMinutes;
    }

    /**
     * @param null $value
     * @return $this
     */
    public function setValue($value = null)
    {
        if ($value === null) {
            $this->date = null;

        } elseif ($value instanceof \DateTimeInterface) {
            $this->date = $value;

        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }


    /**
     * @return \DateTimeInterface
     */
    public function getValue()
    {
        return $this->date;
    }


    /**
     *
     */
    public function loadHttpData()
    {
        $date = $this->getHttpData(Nette\Forms\Form::DATA_LINE);

        if ($date != "") {

            if (!preg_match('/^[0-9]{1,2}\.(| )[0-9]{1,2}\.(| )[12][0-9]{3}$/', $date)) {
                $this->addError("Špatný formát datumu.");
                return;
            }

            try {
                $date = new \DateTime($date);
            } catch (\Exception $e) {
                $this->addError("Špatný formát datumu..");
                return;
            }
        }

        try {
            $this->date = Nette\Utils\DateTime::from($date);
        } catch (\Exception $e) {
            $this->addError("Špatný formát datumu...");
            //    $this->date = null;
        }
    }


    /**
     * @return mixed
     */
    public function getControl()
    {
        $el = parent::getControl();
        if ($this->date !== null) {
            $value = ($this->useMinutes) ? $this->date->format('d.m.Y H:i') : $this->date->format('d.m.Y');
        } else {
            $value = null;
        }

        $el->addAttributes([
            'value' => $value
        ]);

        return $el;
    }


    /**
     * @return bool
     */
    public function isFilled()
    {
        return $this->date !== null;
    }
}