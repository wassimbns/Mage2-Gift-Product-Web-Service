<?php

namespace Magento\Module\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception;
use \Magento\Module\Helper\Data;

class RemovePromoProductObserver implements ObserverInterface
{
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    protected $_productRepository;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $_cart;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

     /**
     * @var Data
     */
    public $_helper;

    /**
     * RemovePromoProductObserver constructor.
     * @param \Magento\Catalog\Model\ProductRepository $productRepository
     * @param \Magento\Checkout\Model\Cart $cart
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Module\Helper\Data $helper
     */
    public function __construct(\Magento\Catalog\Model\ProductRepository $productRepository,
                                \Magento\Checkout\Model\Cart $cart,
                                \Magento\Framework\App\Action\Context $context,
                                \Magento\Checkout\Model\Session $checkoutSession,
                                \Farmasi\Catalog\Helper\Data $helper)
    {
        $this->_productRepository = $productRepository;
        $this->_cart = $cart;
        $this->checkoutSession = $checkoutSession;
        $this->messageManager = $context->getMessageManager();
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $sku = $this->_helper->getGiftProductBySku();
        $quoteItem = $observer->getQuoteItem();
        $quote = $quoteItem->getQuote();
        $countCartItems = $this->_cart->getItemsCount();

        if ($sku != null) {
            $giftProductId = $this->_helper->getGiftProductById();
            if (($countCartItems == 2) && ($this->checkoutSession->getQuote()->hasProductId($giftProductId) == true)) {
                $quote->removeAllItems();
                $quote->save();
            }
        }
    }
}
