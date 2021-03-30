<?php namespace Emipro\Blog\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('emipro_blog'))
            ->addColumn('id',Table::TYPE_SMALLINT,null,['identity' => true, 'nullable' => false, 'primary' => true],'Post ID')
            ->addColumn('name',Table::TYPE_TEXT,255,[],'Author Name')
            ->addColumn('content',Table::TYPE_TEXT,'2M',[],'Blog Content')
            ->setComment('Emipro Blog');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
