<?php

namespace Magento\Module\Model;

use \Exception;
use Magento\Module\Helper\Data;
use Magento\Customer\Model\CustomerFactory;

class FarmasiCustomer implements \Farmasi\Sync\Api\FarmasiCustomerInterface
{

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManager;

    /** @var \Magento\Indexer\Model\IndexerFactory */
    protected $_indexerFactory;

    /** @var \Magento\Framework\ObjectManagerInterface */
    protected $_objectManager;

    /**
     * @var \Farmasi\Sync\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime
     */
    private $dateTime;

    /**
     * @var CustomerFactory
     */
    private $customerFactory;

    /**
     * FarmasiCustomer constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\FilterBuilder $filterBuilder
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Customer\Api\AccountManagementInterface $accountManager
     * @param \Magento\Indexer\Model\IndexerFactory $indexerFactory
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param CustomerFactory $customerFactory
     * @param Data $helper
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManager,
        \Magento\Indexer\Model\IndexerFactory $indexerFactory,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        CustomerFactory $customerFactory,
        Data $helper
    )
    {
        $this->_objectManager = $objectManager;
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->accountManager = $accountManager;
        $this->_indexerFactory = $indexerFactory;
        $this->helper = $helper;
        $this->dateTime = $dateTime;
        $this->customerFactory = $customerFactory;
    }



    /**
     * {@inheritdoc}
     */
    public function getGiftProduct($items)
    {
        $resources = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resources->getConnection();
        $tableName = $resources->getTableName('magento_customer_gift');
        $dataArr = [];

        foreach ($items as $item) {
            $codeSite = $item->getCodeSite();
            $sku = $item->getSku();
            $dataArr[] = "('{$codeSite}', {$sku})";
        }
        $sql = "INSERT INTO {$tableName} (codeSite, sku) values ";
        $sql .= implode(',', $dataArr);
        $sql .= " ON DUPLICATE KEY UPDATE sku=sku";
        try {
            $connection->query($sql);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}
