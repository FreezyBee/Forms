<?php

namespace FreezyBee\Forms\Utils;

use Nette\Utils\Image;

/**
 * Class CropperImage
 * @package FreezyBee\Forms
 */
class CropperImage extends Image
{
    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
