<?php

namespace FreezyBee\Forms\Containers;

use FreezyBee\Forms\Controls\CropperInput;
use FreezyBee\Forms\CropperException;
use FreezyBee\Forms\Services\Cropper;
use FreezyBee\Forms\Utils\CropperImage;
use Nette;
use Nette\Forms\Controls\UploadControl;

/**
 * Class CropperContainer
 * @package FreezyBee\Forms\Controls
 */
class CropperContainer extends Nette\Forms\Container
{
    /** @var Cropper */
    private $cropper;

    /** @var array */
    private $dataParams;

    /**
     * CropperContainer constructor.
     * @param string $name
     * @param string $label
     * @param array $params
     * @param string $containerName
     * @throws Nette\Utils\JsonException
     */
    public function __construct(string $name, string $label, array $params, string $containerName)
    {
        parent::__construct();

        $this->dataParams = $params['data'] ?? [];

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
     * @return CropperImage|null|array
     */
    public function getValues($asArray = false)
    {
        return $this->cropper ? $this->cropper->crop() : [];
    }

    /**
     * Performs the server side validation.
     * @param Nette\Forms\IControl[]
     * @return void
     */
    public function validate(array $controls = null)
    {
        /** @var UploadControl $file */
        $file = $this['file'];

        parent::validate($controls);

        if ($file->isOk()) {
            try {
                /** @var CropperInput $jsonControl */
                $jsonControl = $this['json'];
                $this->cropper = new Cropper($file, $jsonControl, $this->dataParams);
            } catch (CropperException $e) {
                $file->addError($e->getMessage());
            }
        }
    }

    public function setRequired()
    {
        /** @var UploadControl $fileControl */
        $fileControl = $this['file'];
        $fileControl->setRequired();
    }
}
