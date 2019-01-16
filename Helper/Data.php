<?php

namespace Magento\Catalog\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
   
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Data constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(\Magento\Customer\Model\Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    
    /**
     * {@inheritdoc}
     * @var string
     */
    public function getGiftProductBySku()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        if ($customerSession->isLoggedIn()) {
            $customerId = $customerSession->getCustomerId();
            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('magento_customer_gift');
            $sql = "SELECT sku FROM {$tableName} WHERE codeSite = {$customerId}";
            $giftProductSku = $connection->fetchOne($sql);
        }
        if (!empty($giftProductSku)) {
            return (string)$giftProductSku;
        } else
            return null;
    }

    /**
     * {@inheritdoc}
     * @var int
     */
    public function getGiftProductById()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create("Magento\Customer\Model\Session");
        if ($customerSession->isLoggedIn()) {
            $resources = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resources->getConnection();
            $tableName = $resources->getTableName('catalog_product_entity');
            $sql = "SELECT entity_id FROM {$tableName} WHERE sku = {$this->getGiftProductBySku()}";
            $giftProductId = $connection->fetchOne($sql);
        }
        if (!empty($giftProductId)) {
            return (int)$giftProductId;
        } else
            return null;
    }

}
