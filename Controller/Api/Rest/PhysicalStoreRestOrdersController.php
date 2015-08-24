<?php
namespace DemacMedia\Bundle\PhysicalStoreBundle\Controller\Api\Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;



/**
 * @NamePrefix("demacmedia_api_")
 */
class PhysicalStoreRestOrdersController extends RestController implements ClassResourceInterface
{
    /**
     * REST GET list of PhysicalStore Orders
     *
    $response = $oroClient->get('api/rest/latest/physicalstore/orders.json', [
        'query' => [
            'page' => 1,
            'limit' => 5,
        ]
    ]);
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+", nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      resource=true,
     *      description="Get all PhysicalStore Orders",
     * )
     * @AclAncestor("demacmedia_physicalstore_orders")
     * @return Response
     */
    public function cgetAction()
    {
        $page = (int) $this->getRequest()->get('page', 1);
        $limit = (int) $this->getRequest()->get('limit', self::ITEMS_PER_PAGE);

        return $this->handleGetListRequest($page, $limit);
    }


    /**
     * Get specific order data
     *
        // Get a specific account using a id. In this example id=1
        $physicalOrdersResponse = $oroClient->get('api/rest/latest/physicalstore/orders/1.json', []);
     * @param int $id Account id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @ApiDoc(
     * description="Get a specific Physical Store order info",
     * resource=true,
     * requirements={
     * {"name"="id", "dataType"="integer"},
     * }
     * )
     * @AclAncestor("demacmedia_physicalstore_orders_view")
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }


    /**
     * Create new PhysicalStore Order
     *
        // Example creating a new Order.
        $response = $oroClient->post('api/rest/latest/physicalstore/orders.json', [
            'body' => [
                'invno'            => '750831', // Required
                'custno'           => '400200', // Required
                'invdate'          => '07/21/15',
                'shipvia'          => 'Ground',
                'cshipno'          => '1ZA4W9300356834755',
                'taxrate'          => '',
                'tax'              => '',
                'invamt'           => '',
                'ponum'            => '',
                'refno'            => '',
                'salesrep'         => 'Sales Rep Name',
                'status'           => 'Order Status',
                'shipname'         => 'Ship Name (Company)',
                'shipcontact'      => 'Ship Contact Name',
                'shipcontactphone' => '123123123123',
                'shipaddr1'        => 'Address 1',
                'shipaddr2'        => 'Address 2',
                'shipcity'         => 'Chicago',
                'shipstate'        => 'IL',
                'shipzip'          => '123123',
                'shipcountry'      => 'USA',
                'vendorno'         => '11111111',
                'freight'          => '1',
                'dateord'          => '07/21/15',
                'estshpdate'       => '07/21/15',
                'shipdate'         => '07/21/15'
            ]
        ]);
     *
     * @ApiDoc(
     * description="Create new Physical Store Order.",
     * resource=true
     * )
     * @AclAncestor("demacmedia_physicalstore_orders_create")
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }



    /**
     * Update Physical Store Order
     *
        $request = $oroClient->put('api/rest/latest/physicalstore/orders/6.json', [
            'body' => [
                'invno'            => '750831', // Required
                'custno'           => '400200', // Required
                'invdate'          => '07/21/15',
                'shipvia'          => 'Ground',
                'cshipno'          => '1ZA4W9300356834755',
                'taxrate'          => '',
                'tax'              => '',
                'invamt'           => '',
                'ponum'            => '',
                'refno'            => '',
                'salesrep'         => 'Sales Rep Name',
                'status'           => 'Order Status',
                'shipname'         => 'Ship Name (Company)',
                'shipcontact'      => 'Ship Contact Name',
                'shipcontactphone' => '123123123123',
                'shipaddr1'        => 'Address 1',
                'shipaddr2'        => 'Address 2',
                'shipcity'         => 'Chicago',
                'shipstate'        => 'IL',
                'shipzip'          => '123123',
                'shipcountry'      => 'USA',
                'vendorno'         => '11111111',
                'freight'          => '1',
                'dateord'          => '07/21/15',
                'estshpdate'       => '07/21/15',
                'shipdate'         => '07/21/15'
            ]
        ]);
     *
     * @param int $id Comment item id
     *
     * @ApiDoc(
     * description="Update Physical Store Order",
     * resource=true
     * )
     * @AclAncestor("demacmedia_physicalstore_orders_update")
     *
     * @return Response
     */
    public function putAction($id)
    {
        $entity = $this->getManager()->find($id);
        if ($entity) {
            if ($this->processForm($entity)) {
                $view = $this->view($this->getManager()->getEntityViewModel($entity), Codes::HTTP_OK);
            } else {
                $view = $this->view($this->getForm(), Codes::HTTP_BAD_REQUEST);
            }
        } else {
            $view = $this->view(null, Codes::HTTP_NOT_FOUND);
        }
        return $this->buildResponse($view, self::ACTION_UPDATE, ['id' => $id, 'entity' => $entity]);
    }


    /**
     * Delete Physical Store Order
     *
        // Example deleting Account with id: 1
        $response = $oroClient->delete('api/rest/latest/physicalstore/orders/1.json');
     *
     * @param int $id comment id
     *
     * @ApiDoc(
     *      description="Delete PhysicalStore order",
     *      resource=true
     * )
     * @Acl(
     * id="demacmedia_physicalstore_orders_delete",
     *      type="entity",
     *      permission="DELETE",
     *      class="DemacMediaPhysicalStoreBundle:OroPhysicalStoreOrders"
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }



    /**
     * Get entity Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('demacmedia_physicalstore_orders.manager.api');
    }


    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->get('demacmedia_physicalstore_orders.form.orders.api');
    }


    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        return $this->get('demacmedia_physicalstore_orders.form.handler.orders_api');
    }
}

