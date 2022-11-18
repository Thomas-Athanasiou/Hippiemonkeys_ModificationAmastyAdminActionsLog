<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2022 Hippiemonkeys Web Inteligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ModificationAmastyAdminActionsLog
     */

    declare(strict_types=1);


    namespace Hippiemonkeys\ModificationAmastyAdminActionsLog\Model\LogEntry\ResourceModel\Grid;

    use Amasty\AdminActionsLog\Model\LogEntry\ResourceModel\Grid\Collection as ParentCollection,
        Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult,
        Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface as ConfigInterface,
        Magento\Framework\Data\Collection\Db\FetchStrategyInterface,
        Magento\Framework\Data\Collection\EntityFactoryInterface,
        Magento\Framework\Event\ManagerInterface,
        Psr\Log\LoggerInterface;

    class Collection
    extends ParentCollection
    {
        protected const
            STRING_SQL_EXPRESSION_FORMAT = '\'%s\'',
            MODIFICATION_FULL_NAME_TEXT = 'Full Name is not available with custom connection modification',
            MODIFICATION_EMAIL_TEXT = 'Email is not available with custom connection modification';

        /**
         * Constructor
         *
         * @access public
         *
         * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
         * @param \Psr\Log\LoggerInterface $logger
         * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
         * @param \Magento\Framework\Event\ManagerInterface $eventManager
         * @param string $mainTable
         * @param \Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface $config
         * @param null|string $resourceModel
         * @param null|string $identifierName
         * @param null|string $connectionName
         * @throws \Magento\Framework\Exception\LocalizedException
         */
        public function __construct(
            EntityFactoryInterface $entityFactory,
            LoggerInterface $logger,
            FetchStrategyInterface $fetchStrategy,
            ManagerInterface $eventManager,
            $mainTable,
            ConfigInterface $config,
            $resourceModel = null,
            $identifierName = null,
            $connectionName = null
        )
        {
            $this->_config = $config;
            parent::__construct(
                $entityFactory,
                $logger,
                $fetchStrategy,
                $eventManager,
                $mainTable,
                $resourceModel,
                $identifierName,
                $connectionName
            );
        }

        protected function _initSelect()
        {
            if($this->getIsActive())
            {
                SearchResult::_initSelect();
                $this->getSelect()->columns(
                    [
                        'full_name' => new \Zend_Db_Expr(
                            \sprintf(
                                static::STRING_SQL_EXPRESSION_FORMAT,
                                __(static::MODIFICATION_FULL_NAME_TEXT)
                            )
                        ),
                        'email'=> new \Zend_Db_Expr(
                            \sprintf(
                                static::STRING_SQL_EXPRESSION_FORMAT,
                                __(static::MODIFICATION_EMAIL_TEXT)
                            )
                        )
                    ]
                );
            }
            else
            {
                parent::_initSelect();
            }

            return $this;
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