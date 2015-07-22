<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Controller;

use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreAccounts;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PhysicalStoreAccountsController extends Controller
{
    /**
     * @Route("/", name="demacmedia_physicalstore_accounts")
     */
    public function indexAction()
    {
        return $this->render('DemacMediaPhysicalStoreBundle:Default:accounts.html.twig', array(
            'entity_class' => $this->container->getParameter('demacmedia_physicalstore_accounts.entity.class')
        ));
    }


    /**
     * @Route("/view/{custno}", name="demacmedia_physicalstore_accounts_view")
     */
    public function viewAction($custno, OroPhysicalStoreAccounts $entity)
    {
        return $this->render('DemacMediaPhysicalStoreBundle:Default:account-view.html.twig', array(
            'entity'       => $entity,
            'custno'       => $custno
        ));
    }


    /**
     * @Route("/create", name="demacmedia_physicalstore_accounts_create")
     * @Template("DemacMediaPhysicalStoreBundle:Default:accounts-update.html.twig")
     */
    public function createAction()
    {
        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('demacmedia_physicalstore_accounts_create', $this->getRequest());
        $physicalStoreAccounts = new OroPhysicalStoreAccounts();
        return $this->update($physicalStoreAccounts, $formAction);
    }


    /**
     * @Route("/update/{id}", name="demacmedia_physicalstore_accounts_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction(OroPhysicalStoreAccounts $entity)
    {
        $formAction = $this->get('router')->generate('demacmedia_physicalstore_accounts_update', [
            'id' => (int)$entity->getId()
        ]);

        return $this->update($entity, $formAction);
    }


    /**
     * @Route("/delete/{id}", name="demacmedia_physicalstore_accounts_delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id)
    {

    }


    /**
     * @param OroPhysicalStoreAccounts   $entity
     * @param string $formAction
     *
     * @return array
     */
    protected function update(OroPhysicalStoreAccounts $entity, $formAction)
    {
        $saved = false;

        if ($this->get('demacmedia_physicalstore_accounts_simple.form.handler')->process($entity)) {
            if (!$this->getRequest()->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('demacmedia_physicalstore_accounts_simple.controller.saved.message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'demacmedia_physicalstore_accounts_update', 'parameters' => ['id' => $entity->getId()]],
                    ['route' => 'demacmedia_physicalstore_accounts'],
                    $entity
                );
            }
            $saved = true;
        }

        return array(
            'entity'     => $entity,
            'saved'      => $saved,
            'form'       => $this->get('demacmedia_physicalstore_accounts_simple.form.handler')->getForm()->createView(),
            'formAction' => $formAction
        );
    }

}
