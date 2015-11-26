<?php

namespace FreezyBee\Forms\Containers;

use FreezyBee\Forms\Controls\CropperInput;
use FreezyBee\Forms\CropperException;
use FreezyBee\Forms\Services\Cropper;
use Nette;

/**
 * Class CropperContainer
 * @package FreezyBee\Forms\Controls
 */
class CropperContainer extends Nette\Forms\Container
{
    /** @var Cropper */
    private $cropper;

    /** @var array */
    private $dataParams = [];

    /**
     * CropperContainer constructor.
     * @param Nette\ComponentModel\IContainer $name
     * @param null $label
     * @param $params
     * @param $containerName
     * @throws Nette\Utils\JsonException
     */
    public function __construct($name, $label, $params, $containerName)
    {
        parent::__construct();

        $this->dataParams = isset($params['data']) ? $params['data'] : [];

        $this->addUpload('file', $label)
            ->setAttribute('class', 'netteCropperFileUpload')
            ->setAttribute('data-nette-cropper', Nette\Utils\Json::encode($this->dataParams))
            ->setAttribute('data-nette-cropper-name', $containerName)
            ->addCondition(Nette\Forms\Form::FILLED)
            ->addRule(Nette\Forms\Form::IMAGE);

        $this['json'] = new CropperInput('', $params, $containerName);
    }

    /**
     * Returns the values submitted by the form.
     * @param  bool  return values as an array?
     * @return Nette\Utils\ArrayHash|array
     */
    public function getValues($asArray = false)
    {
        return $this->cropper->crop();
    }

    /**
     * Performs the server side validation.
     * @param Nette\Forms\IControl[]
     * @return void
     */
    public function validate(array $controls = null)
    {
        /** @var Nette\Forms\Controls\UploadControl $file */
        $file = $this['file'];

        parent::validate($controls);

        if (!$file->hasErrors()) {
            try {
                $this->cropper = new Cropper($file, $this['json'], $this->dataParams);
            } catch (CropperException $e) {
                $this['file']->addError($e->getMessage());
            }
        }
    }

    public function setRequired()
    {
        $this['file']->setRequired();
    }
}
