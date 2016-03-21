<?php

namespace FreezyBee\Forms\DI;

use Nette\DI\CompilerExtension;

/**
 * Class FormsExtension
 * @package FreezyBee\Forms\DI
 */
class FormsExtension extends CompilerExtension
{
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('formService'))
            ->setClass('FreezyBee\Forms\Services\FormService');
    }
}
