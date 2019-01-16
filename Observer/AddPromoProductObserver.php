<?php

namespace Magento\Module\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use \Magento\Module\Helper\Data;

class AddPromoProductObserver implements ObserverInterface
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
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $sku = $this->_helper->getGiftProductBySku();
        $product = $observer->getEvent()->getProduct();
        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        $quoteItem = $observer->getEvent()->getQuoteItem();

        if ($sku != null) {
            $productId = $this->_helper->getGiftProductById();
            $_promoProduct = $this->_productRepository->get($sku);
            if ($product->getId() == $productId) {
                $quoteItem->setIsGifted(1);
                $quoteItem->setBasePrice(0);
                $quoteItem->setPrice(0);
                $quoteItem->setCustomPrice(0);
                $quoteItem->setOriginalCustomPrice(0);
                $quoteItem->getProduct()->setIsSuperMode(true);
                $quoteItem->setQty(0);
                $quoteItem->save();
            }

            if ($this->checkoutSession->getQuote()->hasProductId($productId) == false) {

                if ($product->getId() != $productId) {
                    $params = array(
                        'product' => $productId,
                        'qty' => 1,
                    );
                    $_promoProduct->setBasePrice(0);
                    $_promoProduct->setPrice(0);
                    $_promoProduct->setCustomPrice(0);
                    $this->_cart->addProduct($_promoProduct, $params);
                }
            }
        }

        return $this;
    }
}
