<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2023 Hippiemonkeys Web Inteligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ModificationAmastyAdminActionsLog
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ModificationAmastyAdminActionsLog\Ui\Component\Listing\Filter;

    use Amasty\AdminActionsLog\Logging\Entity\SaveHandler\Sales\Creditmemo,
        Amasty\AdminActionsLog\Logging\Entity\SaveHandler\Sales\Invoice,
        Amasty\AdminActionsLog\Logging\Entity\SaveHandler\Sales\OrderAddress,
        Amasty\AdminActionsLog\Logging\Entity\SaveHandler\Sales\Shipment,
        Amasty\AdminActionsLog\Ui\Component\Listing\Filter\FilterOrderListing as ParentFilterOrderListing,
        Magento\Framework\App\RequestInterface,
        Magento\Framework\Data\Collection\AbstractDb,
        Magento\Sales\Api\OrderRepositoryInterface,
        Magento\Sales\Model\Order,
        Magento\Sales\Model\ResourceModel\Collection\AbstractCollection as MagentoSalesAbstractCollection,
        Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface as ConfigInterface;

    class FilterOrderListing
    extends ParentFilterOrderListing
    {
        /**
         * Constructor
         *
         * @access public
         *
         * @param \Magento\Framework\App\RequestInterface $request
         * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
         * @param \Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface $config
         */
        public function __construct(
            RequestInterface $request,
            OrderRepositoryInterface $orderRepository,
            ConfigInterface $config
        )
        {
            parent::__construct($request);
            $this->_request = $request;
            $this->_orderRepository = $orderRepository;
            $this->_config = $config;
        }

        /**
         * {@inheritdoc}
         */
        public function apply(AbstractDb $collection)
        {
            if($this->getIsActive())
            {
                $orderId = (int) $this->getRequest()->getParam('order_id');
                if ($orderId)
                {
                    try
                    {
                        $order = $this->getOrderRepository()->get($orderId);
                        if($order instanceof Order)
                        {
                            $select = $collection->getSelect();

                            $creditmemosCollection = $order->getCreditmemosCollection();
                            if($creditmemosCollection)
                            {
                                $creditMemoIds = $this->collectionToIdArray($creditmemosCollection);
                                if(\count($creditMemoIds) > 0)
                                {
                                    $select->where(
                                        \sprintf('category = ? AND element_id IN (%s)', implode(',', $creditMemoIds)),
                                        Creditmemo::CATEGORY,
                                    );
                                }
                            }

                            $invoiceCollection = $order->getInvoiceCollection();
                            if($invoiceCollection)
                            {
                                $invoiceIds = $this->collectionToIdArray($invoiceCollection);
                                if(\count($invoiceIds) > 0)
                                {
                                    $select->orwhere(
                                        \sprintf('category = ? AND element_id IN (%s)', implode(',', $invoiceIds)),
                                        Invoice::CATEGORY,
                                    );
                                }
                            }

                            $shipmentsCollection = $order->getShipmentsCollection();
                            if($shipmentsCollection)
                            {
                                $shipmentIds = $this->collectionToIdArray($shipmentsCollection);
                                if(\count($shipmentIds) > 0)
                                {
                                    $select->orwhere(
                                        \sprintf('category = ? AND element_id IN (%s)',implode(',', $shipmentIds)),
                                        Shipment::CATEGORY
                                    );
                                }
                            }

                            $orderAdressesCollection = $order->getAddressesCollection();
                            if($orderAdressesCollection)
                            {
                                $orderAdressIds = $this->collectionToIdArray($orderAdressesCollection);
                                if(\count($orderAdressIds) > 0)
                                {
                                    $select->orwhere(
                                        \sprintf('category = ? AND element_id IN (%s)',implode(',', $orderAdressIds)),
                                        OrderAddress::CATEGORY
                                    );
                                }
                            }
                        }
                    }
                    catch (\Exception)
                    {

                    }
                }
            }
            else
            {
                parent::apply($collection);
            }
        }

        /**
         * Converts the given Magento Sales Collection to an array of ids
         *
         * @access private
         *
         * @param \Magento\Sales\Model\ResourceModel\Collection\AbstractCollection $collection
         *
         * @return mixed[]
         */
        private function collectionToIdArray(MagentoSalesAbstractCollection $collection): array
        {
            return \array_map(
                function($orderAddress)
                {
                    return (int) $orderAddress->getId();
                },
                $collection->getItems()
            );
        }

        /**
         * Request property
         *
         * @access private
         *
         * @var \Magento\Framework\App\RequestInterface $_request
         */
        private $_request;

        /**
         * Gets Request
         *
         * @access protected
         *
         * @return \Magento\Framework\App\RequestInterface
         */
        protected function getRequest(): RequestInterface
        {
            return $this->_request;
        }

        /**
         * Gets Order Repository
         *
         * @access private
         *
         * @var \Magento\Sales\Api\OrderRepositoryInterface $_orderRepository
         */
        private $_orderRepository;

        /**
         * Gets Order Address Repository
         *
         * @access protected
         *
         * @return \Magento\Sales\Api\OrderRepositoryInterface
         */
        protected function getOrderRepository(): OrderRepositoryInterface
        {
            return $this->_orderRepository;
        }

        /**
         * Gets wether database modification is active or not.
         *
         * @access protected
         *
         * @return bool
         */
        protected function getIsActive(): bool
        {
            return $this->getConfig()->getIsActive();
        }

        /**
         * Config property
         *
         * @access private
         *
         * @var \Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface $_config
         */
        private $_config;

        /**
         * Gets Config
         *
         * @access protected
         *
         * @return \Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface
         */
        protected function getConfig(): ConfigInterface
        {
            return $this->_config;
        }
    }
?>