<?php

namespace Magento\Module\Api\Data;

/**
 * Class GiftProduct
 * @package Magento\Module\Api\Data
 */
class GiftProduct
{
    /** @var int $codeSite */
    public $codeSite;

    /** @var string $sku */
    public $sku;

    /**
     * @return int
     */
    public function getCodeSite()
    {
        return $this->codeSite;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param int $codeSite
     */
    public function setCodeSite($codeSite)
    {
        $this->codeSite = $codeSite;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

}
