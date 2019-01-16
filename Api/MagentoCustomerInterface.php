<?php

namespace Magento\Sync\Api;

/**
 * @api
 */
interface MagentoCustomerInterface
{
    /**
     * @param \Magento\Module\Api\Data\GiftProduct[] $items
     * @return bool
     */
    public function getGiftProduct($items);

}
