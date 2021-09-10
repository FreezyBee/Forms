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
     * @var CropperImage|null
     */
    private $image;

    /**
     * @var bool
     */
    private $cropped = false;

    /**
     * Tohle prislo z FE od klienta
     * @var ArrayHash<mixed>|null
     */
    private $settings;

    /**
     * Nastaveni serveru
     * @var array<string, mixed>
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
     * @param array<string, mixed> $params
     */
    public function __construct(UploadControl $fileControl, CropperInput $jsonControl, array $params = [])
    {
        $this->required = $fileControl->isRequired();
        $this->setImage($fileControl->getValue());
        $this->setSettings($jsonControl->getValue());
        $this->params = $params;
    }

    /**
     * @param string $json
     * @throws CropperException
     */
    private function setSettings($json): void
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
    private function setImage(FileUpload $file): void
    {
        if ($file->isImage() && $file->isOk()) {
            $image = CropperImage::fromFile($file->getTemporaryFile());
            $image->setName($file->getName());

            $imgWidth = $image->getWidth();
            $imgHeight = $image->getHeight();

            $minWidth = $this->params['minWidth'] ?? 0;
            $minHeight = $this->params['minHeight'] ?? 0;

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
        }

        if ($this->required) {
            throw new \Exception('WTF?');
        }

        return null;
    }
}
