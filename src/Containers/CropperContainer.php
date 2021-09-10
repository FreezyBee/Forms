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
    /** @var Cropper|null */
    private $cropper;

    /** @var array<mixed> */
    private $dataParams;

    public function __construct(string $label, array $params, string $containerName)
    {
        $this->dataParams = $params['data'] ?? [];

        $this->addUpload('file', $label)
            ->setHtmlAttribute('class', 'netteCropperFileUpload')
            ->setHtmlAttribute('data-nette-cropper', Nette\Utils\Json::encode($this->dataParams))
            ->setHtmlAttribute('data-nette-cropper-name', $containerName)
            ->addCondition(Nette\Forms\Form::FILLED)
            ->addRule(Nette\Forms\Form::IMAGE);

        $this['json'] = new CropperInput('', $params, $containerName);
    }

    /**
     * {@inheritDoc}
     * @return CropperImage|null|array
     */
    public function getUnsafeValues($returnType = null, array $controls = null)
    {
        return $this->cropper ? $this->cropper->crop() : [];
    }

    /**
     * Performs the server side validation.
     * @param Nette\Forms\Control[]|null $controls
     */
    public function validate(array $controls = null): void
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

    public function setRequired(): void
    {
        /** @var UploadControl $fileControl */
        $fileControl = $this['file'];
        $fileControl->setRequired();
    }
}
