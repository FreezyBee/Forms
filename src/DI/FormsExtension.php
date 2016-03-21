<?php

namespace FreezyBee\Forms\DI;

use Nette\DI\CompilerExtension;

/**
 * Class FormsExtension
 * @package FreezyBee\Forms\DI
 */
class FormsExtension extends CompilerExtension
{
    private $defaults = [
        'applyErrors' => true
    ];
    
    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);

        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('formService'))
            ->setClass('FreezyBee\Forms\Services\FormService')
            ->setArguments([$config]);
    }
}
