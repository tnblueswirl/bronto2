<?php

namespace Bronto\Common\Controller\Adminhtml;

class Apitoken extends \Magento\Backend\Controller\AbstractAction
{
    /**
     * @var \Bronto\Common\Helper\Api
     */
    protected $_apiHelper;

    /**
     * @param \Magento\Backend\Controller\Context $context
     * @param \Bronto\Common\Helper\Api           $apiHelper
     */
    public function __construct(
        \Magento\Backend\Controller\Context $context,
        \Bronto\Common\Helper\Api $apiHelper
    )
    {
        $this->_apiHelper = $apiHelper;

        parent::__construct($context);
        $this->_helper = $context->getHelper();
        $this->_session = $context->getSession();
        $this->_authorization = $context->getAuthorization();
        $this->_translator = $context->getTranslator();
        $this->_auth = $context->getAuth();
        $this->_backendUrl = $context->getBackendUrl();
        $this->_locale = $context->getLocale();
    }

    /**
     * API Token Validation action
     */
    public function verifyTokenAction()
    {
        // Set Default Result Value
        $result = 'Token is Invalid.  Must be 36 characters and should only contain alpha-numeric and - characters.' .
                  'Please get your token from <a href="http://app.bronto.com">http://app.bronto.com</a>.';

        // Get Token Value and perform Validation
        try {
            $groups = $this->getRequest()->getParam('groups');

            if (isset($groups['settings']['fields']['token']['value'])) {
                $token = $groups['settings']['fields']['token']['value'];

                // Validate Token
                if ($this->_apiHelper->validateToken($token)) {
                    $result = 'true';
                }
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        // Set Response
        $this->getResponse()->setBody($this->_apiHelper->jsonEncode($result));
    }
}