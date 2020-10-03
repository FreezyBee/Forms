<?php

namespace FreezyBee\Forms\Controls;

use Nette\Forms\Controls\TextBase;
use Nette\Forms\Form;
use Nette\Utils\DateTime;
use Nette\Utils\Html;

class DateTimeInput extends TextBase
{
    /** @var bool */
    private $useMinutes;

    /** @var bool */
    private $allowNull;

    /**
     * @param string $caption
     * @param bool $useMinutes
     * @param bool $allowNull
     */
    public function __construct($caption = null, $useMinutes = false, $allowNull = false)
    {
        parent::__construct($caption);
        $this->useMinutes = $useMinutes;
        $this->allowNull = $allowNull;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value = null)
    {
        if ($value === null) {
            $this->value = null;

        } elseif ($value instanceof \DateTimeInterface) {
            $this->value = $value;

        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    public function loadHttpData(): void
    {
        $date = $this->getHttpData(Form::DATA_LINE);

        if ($date === '' && $this->allowNull) {
            $this->value = null;
        } else {
            try {
                $this->value = DateTime::from($date);
            } catch (\Exception $e) {
                $this->addError('Špatný formát datumu...');
            }
        }
    }

    public function getControl(): Html
    {
        $el = parent::getControl();
        if ($this->value instanceof \DateTimeInterface) {
            $value = $this->useMinutes ? $this->value->format('d.m.Y H:i') : $this->value->format('d.m.Y');
        } else {
            $value = null;
        }

        $el->addAttributes([
            'value' => $value
        ]);

        return $el;
    }
}
