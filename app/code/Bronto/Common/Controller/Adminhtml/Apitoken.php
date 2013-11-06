<?php

namespace Bronto\Common\Controller\Adminhtml;

class Apitoken extends \Magento\Backend\Controller\AbstractAction
{
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
                if ($this->_objectManager->get('Bronto\Common\Helper\Api')->validateToken($token)) {
                    $result = 'true';
                }
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        // Set Response
        $this->getResponse()->setBody($this->_objectManager->get('Magento\Core\Helper\Data')->jsonEncode($result));
    }
}