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

    namespace Hippiemonkeys\ModificationAmastyAdminActionsLog\Helper\Config;

    use Hippiemonkeys\Core\Api\Helper\ConfigInterface,
        Hippiemonkeys\Core\Helper\Config\Section\Group\Sub,
        Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config\ConnectionInterface,
        Magento\Framework\App\Helper\Context;

    class Connection
    extends Sub
    implements ConnectionInterface
    {
        /**
         * Constructor
         *
         * @access public
         *
         * @param \Magento\Framework\App\Helper\Context $context
         * @param string $section
         * @param string $group
         * @param \Hippiemonkeys\Core\Api\Helper\ConfigInterface $parentConfig
         * @param string $connectionNameField
         */
        public function __construct(
            Context $context,
            string $section,
            string $group,
            ConfigInterface $parentConfig,
            string $connectionNameField
        )
        {
            parent::__construct($context, $section, $group, $parentConfig);
            $this->_connectionNameField = $connectionNameField;
        }

        /**
         * {@inheritdoc}
         */
        public function getConnectionName(): string
        {
            return $this->getData(
                $this->getConnectionNameField()
            );
        }

        /**
         * Connection Name Field property
         *
         * @access private
         *
         * @var string $_modificationFlagField
         */
        private $_connectionNameField;

        /**
         * Gets Connection Name Field
         *
         * @access protected
         *
         * @return string
         */
        protected function getConnectionNameField() : string
        {
            return $this->_connectionNameField;
        }
    }
?>