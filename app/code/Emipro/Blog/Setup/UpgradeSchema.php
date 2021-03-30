<?php namespace Emipro\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
class UpgradeSchema implements  UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup,
                            ModuleContextInterface $context){
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            // Get module table
            $tableName = $setup->getTable('emipro_blog');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'user' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => false,
                        'comment' => 'Login User',
                    ],
                    'status' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'comment' => 'Is Block Active',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }
        if (version_compare($context->getVersion(), '1.0.2') < 0) {

            // Get module table
            $tableName = $setup->getTable('emipro_blog');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'title' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => false,
                        'comment' => 'Post Title',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }
        if (version_compare($context->getVersion(), '1.0.3') < 0) {

            // Get module table
            $tableName = $setup->getTable('emipro_blog');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'createat' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        'nullable' => false,
                        'comment' => 'Post Created Date',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }

        $setup->endSetup();
    }
}