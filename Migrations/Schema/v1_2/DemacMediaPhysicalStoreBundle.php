<?php

namespace DemacMedia\Bundle\DemacMediaPhysicalStoreBundle\Migrations\Schema\v1_2;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtension;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class DemacMediaPhysicalStoreBundle implements Migration, RenameExtensionAwareInterface
{
    /**
     * @var RenameExtension
     */
    protected $renameExtension;


    /**
     * {@inheritdoc}
     */
    public function setRenameExtension(RenameExtension $renameExtension)
    {
        $this->renameExtension = $renameExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->renameContactColumn($schema, $queries);
        $this->addAccountAndContactColumns($schema, $queries);

        $queries->addQuery(
            "INSERT INTO orocrm_contact_source (name, label) VALUES ('instore', 'In-Store')"
        );
    }

    /**
     * Creates PhysicalStoreAccounts table
     *
     * @param Schema $schema
     */
    protected function renameContactColumn(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_physicalstore_accounts');
        $this->renameExtension->renameColumn(
            $schema,
            $queries,
            $table,
            'contact',
            'contactname'
        );
    }

    protected function addAccountAndContactColumns(Schema $schema, QueryBag $queries)
    {
        $table = $schema->getTable('oro_physicalstore_accounts');

        $table->addColumn('account_id', 'integer', ['notnull' => false]);
        $table->addColumn('contact_id', 'integer', ['notnull' => false]);

        $table->addIndex(['account_id'], strtoupper('IDX_physicalstore_account_id'), []);
        $table->addIndex(['contact_id'], strtoupper('IDX_physicalstore_contact_id'), []);
    }
}
