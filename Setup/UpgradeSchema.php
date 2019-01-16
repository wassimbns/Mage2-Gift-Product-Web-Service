<?php

namespace Magento\Module\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
 
        // upgrade table 'magento_customer_gift'

        if (version_compare($context->getVersion(), '1.0.7') < 0) {

            $table = $installer->getConnection()->newTable(
                $installer->getTable('magento_customer_gift'))
                ->addColumn(
                    'codeSite',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'primary' => true],
                    'Code Site'
                )->addColumn(
                    'sku',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'sku'
                )->setComment('Produit cadeau');
            $installer->getConnection()->createTable($table);

            $installer->endSetup();
        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            $this->createPriceUpdateTable($installer);
        }

        $setup->endSetup();
    }

  }
}
