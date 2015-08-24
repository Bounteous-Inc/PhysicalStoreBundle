<?php

namespace DemacMedia\Bundle\DemacMediaPhysicalStoreBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class DemacMediaPhysicalStoreBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addSalesRepAccounts($schema);
        $this->addFieldsOnOrders($schema);
        $this->addPonumOrderItems($schema);
    }


    /**
     * Creates PhysicalStoreAccounts table
     *
     * @param Schema $schema
     */
    protected function addSalesRepAccounts(Schema $schema)
    {
        $table = $schema->getTable('oro_physicalstore_accounts');
        $table->addColumn('salesrep', 'string', ['length' => 48, 'notnull' => false]);
    }


    /**
     * Creates PhysicalStoreOrders table
     *
     * @param Schema $schema
     */
    protected function addFieldsOnOrders(Schema $schema)
    {
        $table = $schema->getTable('oro_physicalstore_orders');
        $table->addColumn('salesrep',           'string', ['length' => 48, 'notnull' => false]);
        $table->addColumn('status',             'string', ['length' => 32, 'notnull' => false]);
        $table->addColumn('shipname',           'string', ['length' => 64, 'notnull' => false]);
        $table->addColumn('shipcontact',        'string', ['length' => 64, 'notnull' => false]);
        $table->addColumn('shipcontactphone',   'string', ['length' => 32, 'notnull' => false]);
        $table->addColumn('shipaddr1',          'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('shipaddr2',          'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('shipcity',           'string', ['length' => 64, 'notnull' => false]);
        $table->addColumn('shipstate',          'string', ['length' => 32, 'notnull' => false]);
        $table->addColumn('shipzip',            'string', ['length' => 12, 'notnull' => false]);
        $table->addColumn('shipcountry',        'string', ['length' => 64, 'notnull' => false]);
        $table->addColumn('vendorno',           'string', ['length' => 48, 'notnull' => false]);
        $table->addColumn('freight',            'string', ['length' => 32, 'notnull' => false]);
        $table->addColumn('dateord',            'datetime', ['notnull' => false]);
        $table->addColumn('estshpdate',         'datetime', ['notnull' => false]);
        $table->addColumn('shipdate',           'datetime', ['notnull' => false]);

        $table->addIndex(['salesrep'],          strtoupper('IDX_orders_salesrep'), []);
        $table->addIndex(['status'],            strtoupper('IDX_orders_status'), []);
        $table->addIndex(['shipname'],          strtoupper('IDX_orders_shipname'), []);
        $table->addIndex(['shipcontact'],       strtoupper('IDX_orders_shipcontact'), []);
        $table->addIndex(['shipcontactphone'],  strtoupper('IDX_orders_shipcontactphone'), []);
        $table->addIndex(['shipaddr1'],         strtoupper('IDX_orders_shipaddr1'), []);
        $table->addIndex(['shipcity'],          strtoupper('IDX_orders_shipcity'), []);
        $table->addIndex(['vendorno'],          strtoupper('IDX_orders_vendorno'), []);
    }


    /**
     * Creates PhysicalStoreOrdersItems table
     *
     * @param Schema $schema
     */
    protected function addPonumOrderItems(Schema $schema)
    {
        $table = $schema->getTable('oro_physicalstore_order_items');
        $table->addColumn('ponum', 'string', ['length' => 32, 'notnull' => false]);
    }
}
