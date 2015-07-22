<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreAccounts;

class PhysicalStoreAccountsApiHandler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     *
     * @param FormInterface $form
     * @param Request       $request
     * @param ObjectManager $manager
     */
    public function __construct(FormInterface $form, Request $request, ObjectManager $manager)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * Process form
     *
     * @param  OroPhysicalStoreAccounts $entity
     * @return bool True on successful processing, false otherwise
     */
    public function process(OroPhysicalStoreAccounts $entity)
    {
        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * "Success" form handler
     *
     * @param OroPhysicalStoreAccounts $entity
     */
    protected function onSuccess(OroPhysicalStoreAccounts $entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }
}
