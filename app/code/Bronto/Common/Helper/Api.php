<?php

/**
 * Bronto Common Api Helper
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 */
namespace Bronto\Common\Helper;

class Api extends Data
{
    /**
     * Constant variables to hold System Config value paths
     */
    const XML_PATH_SETTINGS_TOKEN   = 'bronto_common/settings/token';

    /**
     * @var
     */
    protected $_token;

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
        parent::__construct(
            $moduleManager,
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
     * Performs validation on provided $token, including logging into the API.
     *
     * @param null $token
     *
     * @return bool
     */
    public function validateToken($token = null)
    {
        if (!$token) {
            $token = $this->getToken();
        }

        // Ensure Token is letters, numbers, dash and is 36 characters long
        if (!preg_match('/[A-Z0-9\-]{36}/', $token)) {
            return false;
        }

        // Login to API with Token and get Token Object Info
        try {
            $api = new \Bronto\Api($token, array('debug' => true));
            $api->login();

            /** @var \Bronto\Api\ApiToken\Row $tokenRow */
            $tokenRow = $api->getTokenInfo();
        } catch (\Exception $e) {
            return false;
        }

        // Return whether or not token has appropriate permissions
        return (bool)$tokenRow->hasPermissions(7);
    }

    /**
     * Set Token Param
     *
     * @param $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->_token = $token;

        return $this;
    }

    /**
     * Retrieve API Token from Config
     *
     * @return mixed
     */
    public function getToken()
    {
        if (!$this->_token) {
            $this->setToken($this->getScopedConfig(self::XML_PATH_SETTINGS_TOKEN));
        }

        return $this->_token;
    }
}