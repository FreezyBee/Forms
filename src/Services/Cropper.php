<?php

namespace FreezyBee\Forms\Services;

use FreezyBee\Forms\Controls\CropperInput;
use FreezyBee\Forms\CropperException;
use FreezyBee\Forms\Utils\CropperImage;

use Nette\Forms\Controls\UploadControl;
use Nette\Http\FileUpload;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

/**
 * Class Cropper
 * @package FreezyBee\Forms\Services
 */
class Cropper
{
    use SmartObject;

    /**
     * @var CropperImage
     */
    private $image;

    /**
     * @var bool
     */
    private $cropped = false;

    /**
     * Tohle prislo z FE od klienta
     * @var ArrayHash
     */
    private $settings;

    /**
     * Nastaveni serveru
     * @var array
     */
    private $params;

    /**
     * @var bool
     */
    private $required;

    /**
     * Cropper constructor.
     * @param UploadControl $fileControl
     * @param CropperInput $jsonControl
     * @param array $params
     */
    public function __construct(UploadControl $fileControl, CropperInput $jsonControl, $params = [])
    {
        $this->required = $fileControl->isRequired();
        $this->setImage($fileControl->getValue());
        $this->setSettings($jsonControl->getValue());
    }

    /**
     * @param string $json
     * @throws CropperException
     */
    private function setSettings($json)
    {
        try {
            $this->settings = Json::decode($json);
        } catch (JsonException $e) {
            throw new CropperException('invalid json');
        }
    }

    /**
     * @param FileUpload $file
     * @throws CropperException
     */
    private function setImage(FileUpload $file)
    {
        if ($file->isImage() && $file->isOk()) {
            $image = CropperImage::fromFile($file->getTemporaryFile());
            $image->setName($file->getName());

            $imgWidth = $image->getWidth();
            $imgHeight = $image->getHeight();

            $minWidth = (isset($this->params['minWidth']) ? $this->params['minWidth'] : 0);
            $minHeight = (isset($this->params['minHeight']) ? $this->params['minHeight'] : 0);

            if ($imgHeight < $minHeight || $imgWidth < $minWidth) {
                throw new CropperException('bad size');
            } else {
                $this->image = $image;
            }
        } elseif ($this->required) {
            throw new CropperException('invalid image');
        }
    }

    /**
     * @return CropperImage|null
     * @throws \Exception
     */
    public function crop()
    {
        if ($this->cropped) {
            return $this->image;
        }

        if ($this->image && $this->settings) {
            $settings = $this->settings;

            $cropX = (int) $settings->x;
            $cropY = (int) $settings->y;
            $cropWidth = (int) $settings->width;
            $cropHeight = (int) $settings->height;

            $this->cropped = true;
            return $this->image->crop($cropX, $cropY, $cropWidth, $cropHeight);
        } elseif ($this->required) {
            throw new \Exception('WTF?');
        } else {
            return null;
        }
    }
}