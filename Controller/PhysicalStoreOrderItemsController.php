<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreOrderItems;

class PhysicalStoreOrderItemsController extends Controller
{
    /**
     * @Route("/", name="demacmedia_physicalstore_order_items")
     */
    public function indexAction()
    {
        return $this->render('DemacMediaPhysicalStoreBundle:Default:order_items.html.twig', array(
            'entity_class' => $this->container->getParameter('demacmedia_physicalstore_order_items.entity.class')
        ));
    }


    /**
     * @Route("/view/{invno}", name="demacmedia_physicalstore_order_items")
     */
    public function viewAction($invno, OroPhysicalStoreOrderItems $entity)
    {
        return $this->render('DemacMediaPhysicalStoreBundle:Default:account-view.html.twig', [
            'entity' => $entity,
            'invno' => $invno
        ]);
    }


    /**
     * @Route("/create", name="demacmedia_physicalstore_order_items_create")
     * @Template("DemacMediaPhysicalStoreBundle:Default:order_items-update.html.twig")
     */
    public function createAction()
    {
        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('demacmedia_physicalstore_order_items_create', $this->getRequest());
        $physicalStoreOrderItems = new OroPhysicalStoreOrderItems();
        return $this->update($physicalStoreOrderItems, $formAction);
    }


    /**
     * @Route("/update/{id}", name="demacmedia_physicalstore_order_items_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction(OroPhysicalStoreOrderItems $entity)
    {
        $formAction = $this->get('router')->generate('demacmedia_physicalstore_order_items_update', ['id' => $entity->getId()]);

        return $this->update($entity, $formAction);
    }


    /**
     * @Route("/delete/{id}", name="demacmedia_physicalstore_order_items_delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id)
    {

    }


    /**
     * @param OroPhysicalStoreOrderItems   $entity
     * @param string $formAction
     *
     * @return array
     */
    protected function update(OroPhysicalStoreOrderItems $entity, $formAction)
    {
        $saved = false;

        if ($this->get('demacmedia_physicalstore_order_items_simple.form.handler')->process($entity)) {
            if (!$this->getRequest()->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('demacmedia_physicalstore_order_items_simple.controller.saved.message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'demacmedia_physicalstore_order_items_update', 'parameters' => ['id' => $entity->getId()]],
                    ['route' => 'demacmedia_physicalstore_order_items'],
                    $entity
                );
            }
            $saved = true;
        }

        return array(
            'entity'     => $entity,
            'saved'      => $saved,
            'form'       => $this->get('demacmedia_physicalstore_order_items_simple.form.handler')->getForm()->createView(),
            'formAction' => $formAction
        );
    }

}
