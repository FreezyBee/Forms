<?php

namespace FreezyBee\Forms\Services;

use Nette\Object;
use Nette\Forms\Form;

use Kdyby\Doctrine\EntityManager;
use Kdyby\DoctrineForms\EntityFormMapper;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FormService
 * @package FreezyBee\Forms\Services
 */
class FormService extends Object
{
    /** @var EntityManager */
    private $em;

    /** @var EntityFormMapper */
    private $formMapper;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * BaseRepository constructor.
     * @param EntityManager $em
     * @param EntityFormMapper $formMapper
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManager $em, EntityFormMapper $formMapper, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->formMapper = $formMapper;
        $this->validator = $validator;
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
     */
    public function softSaveForm($entity, Form &$form)
    {
        $errors = $this->validate($entity);
        if (count($errors)) {
            throw new ValidatorException($errors);
        }

        $this->formMapper->save($entity, $form);
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
