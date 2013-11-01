<?php

/**
 * Bronto Common Helper
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 */
namespace Bronto\Common\Helper;

class Data extends \Magento\Core\Helper\Data
{

    /**#@+
     * Constant variables to hold System Config value paths
     */
    const XML_PATH_SETTINGS_ENABLED = 'bronto_common/settings/enabled';
    const XML_PATH_SETTINGS_TOKEN   = 'bronto_common/settings/token';
    const XML_PATH_SETTINGS_DEBUG   = 'bronto_common/settings/debug';
    const XML_PATH_SETTINGS_VERBOSE = 'bronto_common/settings/verbose';
    const XML_PATH_SETTINGS_TEST    = 'bronto_common/settings/test';
    const XML_PATH_SETTINGS_NOTICES = 'bronto_common/settings/notices';
    /**#@-*/

    /**#@+
     * Scope Constants
     */
    const SCOPE_WEBSITE = 'website';
    const SCOPE_STORE   = 'store';
    const SCOPE_DEFAULT = 'default';
    /**#@-*/

    /**#@+
     * Scope Values
     *
     * @var mixed
     */
    protected $_scope;
    protected $_scopeId;
    protected $_scopeCode;
    protected $_scopeObject;
    /**#@-*/

    /**
     * Get if Module is Enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        // If module is disabled or output is disabled, return false
        if (!\Magento\Core\Model\ModuleManager::isEnabled($this->_getModuleName()) || !\Magento\Core\Model\ModuleManager::isOutputEnabled($this->_getModuleName())) {
            return false;
        }

        // If API Token is not present or is not valid, return false
        if (!$this->isTokenValid()) {
            return false;
        }

        // Return Boolean Value of enabled config value
        return (bool) $this->getScopedConfig(self::XML_PATH_SETTINGS_ENABLED);
    }

    /**
     * Validate API Token
     *
     * @return bool
     */
    public function isTokenValid()
    {
        // Get Token from Config
        $token = $this->getScopedConfig(self::XML_PATH_SETTINGS_TOKEN);

        // Check if token is set
        if (!$token || strlen($token) != 36 || !$this->validateToken()) {
            return false;
        }

        return true;
    }

    /**
     * Set Scope for Helper
     *
     * @param $scope
     * @param $scopeId
     *
     * @return $this
     */
    public function setScope($scope, $scopeId)
    {
        // If provided scope is not valid, get current scope
        if (!in_array($scope, array('default', 'store', 'stores', 'website', 'websites'))) {
            $this->getCurrentScope();
        }

        // If scope is 'default', we know the values so set and return
        if ($scope == 'default') {
            $this->_scope       = 'default';
            $this->_scopeId     = 0;
            $this->_scopeCode   = 0;
            $this->_scopeObject = false;

            return $this;
        }

        // Ensure scope is singular.  Use getScope(true) for pluralized version
        $this->_scope = (substr($scope, -1) == 's') ? substr($scope, 0, strlen($scope)-1) : $scope;

        if ($this->_scopeObject = $this->getScopeObject()) {
            $this->_scopeObject->load($scopeId);

            $this->_scopeId   = $this->_scopeObject->getId();
            $this->_scopeCode = $this->_scopeObject->getCode();
        } else {
            $this->getCurrentScope();
        }

        return $this;
    }

    /**
     * Get Config Value for Current Scope
     *
     * @param $path
     *
     * @return mixed
     */
    public function getScopedConfig($path)
    {
        return $this->_config->getValue($path, $this->getScope(), $this->getScopeCode());
    }
}