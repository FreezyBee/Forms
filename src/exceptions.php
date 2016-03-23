<?php

namespace FreezyBee\Forms;

use Symfony\Component\Validator\ConstraintViolationList;

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
     * @var ConstraintViolationList
     */
    private $errors;
    
    /**
     * @var ConstraintViolationList
     */
    private $unclassifiableErrors;

    /**
     * ValidatorException constructor.
     * @param ConstraintViolationList $errors
     * @param ConstraintViolationList $unclassifiableErrors
     */
    public function __construct(ConstraintViolationList $errors, ConstraintViolationList $unclassifiableErrors)
    {
        parent::__construct((string)$errors);
        $this->errors = $errors;
        $this->unclassifiableErrors = $unclassifiableErrors;
    }

    /**
     * @return ConstraintViolationList
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return ConstraintViolationList
     */
    public function getUnclassifiableErrors()
    {
        return $this->unclassifiableErrors;
    }
}
