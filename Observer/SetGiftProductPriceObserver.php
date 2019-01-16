<?php

namespace Magento\Module\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use \Magento\Module\Helper\Data;

class SetGiftProductPriceObserver implements ObserverInterface
{
    /**
     * @var bool
     */
    static $isCollected = false;

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;

    /**
     * @var Data
     */
    public $_helper;

    /**
     * AddPromoProductObserver constructor.
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Module\Helper\Data $helper
     */
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Farmasi\Catalog\Helper\Data $helper
    )
    {
        $this->_productRepository = $productRepository;
        $this->_cart = $cart;
        $this->checkoutSession = $checkoutSession;
        $this->_helper = $helper;
    }

    /**
     * @param EventObserver $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $sku = $this->_helper->getGiftProductBySku();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();
        if ($sku != null) {
            $giftProductId = $this->_helper->getGiftProductById();
            foreach ($quote->getAllItems() as $quoteItem) {
                if ($quoteItem->getProductId() == $giftProductId) {
                    $quoteItem->setCustomPrice(0);
                    $quoteItem->setOriginalCustomPrice(0);
                    $quoteItem->getProduct()->setIsSuperMode(true);
                    $quoteItem->setQty(0);
                    $quoteItem->save();
                }
            }
        }
        return $this;
    }

}
