<?php

namespace FreezyBee\Forms;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class CropperException
 * @package FreezyBee\Forms
 */
class CropperException extends \Exception
{
}

/**
 * Class ValidatorException
 * @package App\Model\Utils
 */
class ValidatorException extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    /**
     * ValidatorException constructor.
     * @param ConstraintViolationListInterface $errors
     */
    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct((string)$errors);
        $this->errors = $errors;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
