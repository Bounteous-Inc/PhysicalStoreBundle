<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/about", name="demacmedia_physicalstore_about")
 */
class DefaultController extends Controller
{
    public function indexAction() {
        $name = 'foo';

        return $this->render('DemacMediaPhysicalStoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
