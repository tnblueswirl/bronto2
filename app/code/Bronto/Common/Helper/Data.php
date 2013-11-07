<?php

/**
 * Bronto Common Helper
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 */
namespace Bronto\Common\Helper;

use Magento\Core\Model\Config\Cache\Exception;

class Data extends \Magento\Core\Helper\Data
{

    /**#@+
     * Constant variables to hold System Config value paths
     */
    const XML_PATH_SETTINGS_ENABLED = 'bronto_common/settings/enabled';
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
    protected $_scope       = false;
    protected $_scopeId     = false;
    protected $_scopeCode   = false;
    protected $_scopeObject = false;
    /**#@-*/

    protected $_moduleManager;

    /**
     * @param \Magento\Core\Model\ModuleManager $moduleManager
     * @param \Magento\Core\Helper\Context $context
     * @param \Magento\Event\ManagerInterface $eventManager
     * @param \Magento\Core\Helper\Http $coreHttp
     * @param \Magento\Core\Model\Config $config
     * @param \Magento\Core\Model\Store\Config $coreStoreConfig
     * @param \Magento\Core\Model\StoreManager $storeManager
     * @param \Magento\Core\Model\Locale $locale
     * @param \Magento\Core\Model\Date $dateModel
     * @param \Magento\App\State $appState
     * @param \Magento\Core\Model\Encryption $encryptor
     * @param bool $dbCompatibleMode
     */
    public function __construct(
        \Magento\Core\Model\ModuleManager $moduleManager,
        \Magento\Core\Helper\Context $context,
        \Magento\Event\ManagerInterface $eventManager,
        \Magento\Core\Helper\Http $coreHttp,
        \Magento\Core\Model\Config $config,
        \Magento\Core\Model\Store\Config $coreStoreConfig,
        \Magento\Core\Model\StoreManager $storeManager,
        \Magento\Core\Model\Locale $locale,
        \Magento\Core\Model\Date $dateModel,
        \Magento\App\State $appState,
        \Magento\Core\Model\Encryption $encryptor,
        $dbCompatibleMode = true
    ) {
        $this->_moduleManager = $moduleManager;
        parent::__construct(
            $context,
            $eventManager,
            $coreHttp,
            $config,
            $coreStoreConfig,
            $storeManager,
            $locale,
            $dateModel,
            $appState,
            $encryptor,
            $dbCompatibleMode
        );
    }

    /**
     * Get if Module is Enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        // If module is disabled or output is disabled, return false
        if (!$this->_moduleManager->isEnabled($this->_getModuleName()) || !$this->_moduleManager->isOutputEnabled($this->_getModuleName())) {
            return false;
        }

        // Return Boolean Value of enabled config value
        return (bool) $this->getScopedConfig(self::XML_PATH_SETTINGS_ENABLED);
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
     * Get the Scope
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getScope($plural = false)
    {
        // If Scope is not defined, get the current scope
        if (false === $this->_scope) {
            $this->getCurrentScope();
        }

        // Clean Scope and Return
        return $this->_cleanScope($plural)->_scope;
    }

    /**
     * Get the Scope ID
     *
     * @return mixed
     */
    public function getScopeId()
    {
        if (false === $this->_scopeId) {
            $this->getCurrentScope();
        }

        // return Scope ID
        return $this->_scopeId;
    }

    /**
     * Get the Scope Code
     *
     * @return mixed
     */
    public function getScopeCode()
    {
        if (false === $this->_scopeCode) {
            $this->getCurrentScope();
        }

        // return Scope Code
        return $this->_scopeCode;
    }

    /**
     * @return bool|\Magento\Core\Model\Website
     */
    public function getScopeObject()
    {
        if (false === $this->getScope()) {
            $this->getCurrentScope();
        }

        if ('default' == $this->getScope()) {
            return false;
        }

        // Load Scope Object
        if ('store' == $this->getScope()) {
            $this->_scopeObject = $this->_storeManager->getStore($this->getScopeId());
        } elseif ('website' == $this->getScope()) {
            $this->_scopeObject = $this->_storeManager->getWebsite($this->getScopeId());
        }

        return $this->_scopeObject;
    }

    /**
     * Handle pluralizing/singularizing scope as requested
     *
     * @param bool $plural
     *
     * @return $this
     */
    private function _cleanScope($plural = false)
    {
        if ($this->_scope != 'default') {
            if ($plural && substr($this->_scope, -1) != 's') {
                $this->_scope .= 's';
            } elseif (!$plural && substr($this->_scope, -1) == 's') {
                $this->_scope = substr($this->_scope, 0, strlen($this->_scope)-1);
            }
        }

        return $this;
    }

    /**
     * Gets the Current Scope and sets the Helper scope params
     *
     * @return $this
     */
    public function getCurrentScope()
    {
        // Get Scope from Request
        $this->_scope = $this->_request->getParam('store')
            ? self::SCOPE_STORE
            : ($this->_request->getParam('website') ? self::SCOPE_WEBSITE : self::SCOPE_DEFAULT);

        // Set the Scope
        $this->setScope($this->_scope, $this->_request->getParam($this->_scope));

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