<?php
    /**
     * @Thomas-Athanasiou
     *
     * @author Thomas Athanasiou {thomas@hippiemonkeys.com}
     * @link https://hippiemonkeys.com
     * @link https://github.com/Thomas-Athanasiou
     * @copyright Copyright (c) 2023 Hippiemonkeys Web Intelligence EE All Rights Reserved.
     * @license http://www.gnu.org/licenses/ GNU General Public License, version 3
     * @package Hippiemonkeys_ModificationAmastyAdminActionsLog
     */

    declare(strict_types=1);

    namespace Hippiemonkeys\ModificationAmastyAdminActionsLog\Plugin;

    use Magento\Framework\DB\Adapter\AdapterInterface,
        Magento\Framework\App\ResourceConnection,
        Magento\Framework\Model\ResourceModel\Db\AbstractDb,
        Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface as ConfigInterface;

    class ConnectionPlugin
    {
        /**
         * Constructor
         *
         * @access public
         *
         * @param \Magento\Framework\App\ResourceConnection $resourceConnection
         * @param \Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface $config
         */
        public function __construct(
            ResourceConnection $resourceConnection,
            ConfigInterface $config
        )
        {
            $this->_resourceConnection = $resourceConnection;
            $this->_config = $config;
        }

        /**
         * After get connection plugin intersection
         *
         * @access public
         *
         * @param mixed $subject
         * @param \Magento\Framework\DB\Adapter\AdapterInterface|false $connection
         *
         * @return \Magento\Framework\DB\Adapter\AdapterInterface|false
         */
        public function afterGetConnection($subject, AdapterInterface|false $connection): AdapterInterface|false
        {
            if($this->getIsActive())
            {
                $connectionName = $this->getConnectionName();
                if($connectionName)
                {
                    $connection = $this->getResourceConnection()->getConnection($connectionName);
                }
            }
            return $connection;
        }

        /**
         * Gets Connection Name
         *
         * @access protected
         *
         * @return string
         */
        protected function getConnectionName(): string
        {
            return $this->getConfig()->getConnectionName();
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

        /**
         * Resource Connection property
         *
         * @access private
         *
         * @var \Magento\Framework\App\ResourceConnection $_resourceConnection
         */
        private $_resourceConnection;

        /**
         * Gets Resource Connection
         *
         * @access protected
         *
         * @return \Magento\Framework\App\ResourceConnection
         */
        protected function getResourceConnection(): ResourceConnection
        {
            return $this->_resourceConnection;
        }
    }
?>