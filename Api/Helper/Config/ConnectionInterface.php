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

    namespace Hippiemonkeys\ModificationAmastyAdminActionsLog\Api\Helper\Config;

    /**
     * Connection Config helper interface
     */
    interface ConnectionInterface
    {
        /**
         * Gets Connection Name
         *
         * @access public
         *
         * @return string
         */
        public function getConnectionName(): string;
    }
?>