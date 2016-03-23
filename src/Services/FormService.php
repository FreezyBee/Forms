<?php

namespace FreezyBee\Forms\Services;

use FreezyBee\Forms\ValidatorException;
use Nette\Object;
use Nette\Forms\Form;

use Kdyby\Doctrine\EntityManager;
use Kdyby\DoctrineForms\EntityFormMapper;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FormService
 * @package FreezyBee\Forms\Services
 */
class FormService extends Object
{
    /** @var array */
    private $config;

    /** @var EntityManager */
    private $em;

    /** @var EntityFormMapper */
    private $formMapper;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * BaseRepository constructor.
     * @param array $config
     * @param EntityManager $em
     * @param EntityFormMapper $formMapper
     * @param ValidatorInterface $validator
     */
    public function __construct(
        array $config,
        EntityManager $em,
        EntityFormMapper $formMapper,
        ValidatorInterface $validator
    ) {
        $this->em = $em;
        $this->formMapper = $formMapper;
        $this->validator = $validator;
        $this->config = $config;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param $entity
     * @param Form $form
     */
    public function loadFormDefaults($entity, Form &$form)
    {
        $this->formMapper->load($entity, $form);
    }

    /**
     * @param $entity
     * @param Form $form
     * @throws ValidatorException
     */
    public function softSaveForm($entity, Form &$form)
    {
        $this->formMapper->save($entity, $form);

        $errors = $this->validate($entity);
        $unclassifiableErrors = new ConstraintViolationList;

        if (count($errors)) {
            if ($this->config['applyErrors']) {
                foreach ($errors as $error) {
                    $component = $form->getComponent($error->getPropertyPath(), false);
                    if ($component) {
                        $component->addError($error->getMessage());
                    } else {
                        $unclassifiableErrors->add($error);
                    }
                }
            }
            
            throw new ValidatorException($errors, $unclassifiableErrors);
        }
    }

    /**
     * @param $entity
     * @param Form $form
     */
    public function saveForm($entity, Form &$form)
    {
        $this->softSaveForm($entity, $form);
        $this->em->persist($entity)->flush();
    }

    /**
     * @param $entity
     * @return ConstraintViolationListInterface
     */
    public function validate($entity)
    {
        return $this->validator->validate($entity);
    }
}
