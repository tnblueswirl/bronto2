<?php

/**
 * Bronto API Token Field Backend Model
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 */
namespace Bronto\Common\Model\Config;

class Apitoken extends \Magento\Core\Model\Config\Value
{
    /**
     * @var \Bronto\Common\Helper\Api
     */
    protected $_apiHelper;

    /**
     * Constructor
     *
     * @param \Magento\Core\Model\Context                   $context
     * @param \Magento\Core\Model\Registry                  $registry
     * @param \Magento\Core\Model\StoreManager              $storeManager
     * @param \Magento\Core\Model\Config                    $config
     * @param \Bronto\Common\Helper\Api                     $apiHelper
     * @param \Magento\Core\Model\Resource\AbstractResource $resource
     * @param \Magento\Data\Collection\Db                   $resourceCollection
     * @param array                                         $data
     */
    public function __construct(
        \Magento\Core\Model\Context $context,
        \Magento\Core\Model\Registry $registry,
        \Magento\Core\Model\StoreManager $storeManager,
        \Magento\Core\Model\Config $config,
        \Bronto\Common\Helper\Api $apiHelper,
        \Magento\Core\Model\Resource\AbstractResource $resource = null,
        \Magento\Data\Collection\Db $resourceCollection = null,
        array $data = array()
    )
    {
        $this->_apiHelper = $apiHelper;
        parent::__construct($context, $registry, $storeManager, $config, $resource, $resourceCollection, $data);
    }

    /**
     * Check if Token is valid before saving
     *
     * @return $this|\Magento\Core\Model\AbstractModel
     * @throws \Magento\Core\Exception
     */
    public function _beforeSave()
    {
        $value = $this->getValue();

        if (!$this->_apiHelper->validateToken($value)) {
            $message = 'Token is Invalid.  Must be 36 characters and should only contain alpha-numeric and - characters.' .
                'Please get your token from <a href="http://app.bronto.com">http://app.bronto.com</a>.';

            throw new \Magento\Core\Exception(
            //@codingStandardsIgnoreStart
                __($message)
            //@codingStandardsIgnoreEnd
            );
        }

        return $this;
    }
}