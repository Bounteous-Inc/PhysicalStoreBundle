<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use DemacMedia\Bundle\PhysicalStoreBundle\Entity\OroPhysicalStoreOrders;

class PhysicalStoreOrdersController extends Controller
{
    /**
     * @Route("/", name="demacmedia_physicalstore_orders")
     */
    public function indexAction()
    {
        return $this->render('DemacMediaPhysicalStoreBundle:Default:orders.html.twig', [
            'entity_class' => $this->container->getParameter('demacmedia_physicalstore_orders.entity.class')
        ]);
    }


    /**
     * @Route("/view/{invno}", name="demacmedia_physicalstore_orders_view")
     */
    public function viewAction($invno, OroPhysicalStoreOrders $entityOrders)
    {
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $entityAccounts = $this->em->getRepository('DemacMediaPhysicalStoreBundle:OroPhysicalStoreAccounts')
            ->findOneBy([
                'custno' => $entityOrders->getCustno()
            ]);

        if (!$entityAccounts) {
            // throw new EntityNotFoundException();
            printf('There is no order_items for this inventory: %d', $invno);
            die();
        }

        return $this->render('DemacMediaPhysicalStoreBundle:Default:orders-specific-view.html.twig', [
            'entityAccounts' => $entityAccounts,
            'entity' => $entityOrders,
            'invno' => $invno
        ]);
    }


    /**
     * @Route("/create", name="demacmedia_physicalstore_orders_create")
     * @Template("DemacMediaPhysicalStoreBundle:Default:orders-update.html.twig")
     */
    public function createAction()
    {
        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('demacmedia_physicalstore_orders_create', $this->getRequest());
        $physicalStoreOrders = new OroPhysicalStoreOrders();
        return $this->update($physicalStoreOrders, $formAction);
    }


    /**
     * @Route("/update/{id}", name="demacmedia_physicalstore_orders_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction(OroPhysicalStoreOrders $entity)
    {
        $formAction = $this->get('router')->generate('demacmedia_physicalstore_orders_update', ['id' => $entity->getId()]);

        return $this->update($entity, $formAction);
    }


    /**
     * @Route("/delete/{id}", name="demacmedia_physicalstore_orders_delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id)
    {

    }


    /**
     * @param OroPhysicalStoreOrders   $entity
     * @param string $formAction
     *
     * @return array
     */
    protected function update(OroPhysicalStoreOrders $entity, $formAction)
    {
        $saved = false;

        if ($this->get('demacmedia_physicalstore_orders_simple.form.handler')->process($entity)) {
            if (!$this->getRequest()->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('demacmedia_physicalstore_orders_simple.controller.saved.message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'demacmedia_physicalstore_orders_update', 'parameters' => ['id' => $entity->getId()]],
                    ['route' => 'demacmedia_physicalstore_orders'],
                    $entity
                );
            }
            $saved = true;
        }

        return array(
            'entity'     => $entity,
            'saved'      => $saved,
            'form'       => $this->get('demacmedia_physicalstore_orders_simple.form.handler')->getForm()->createView(),
            'formAction' => $formAction
        );
    }

}
