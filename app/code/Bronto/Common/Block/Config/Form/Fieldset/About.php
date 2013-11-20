<?php

/**
 * Bronto Common Api Token Field Frontend Model
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 *
 * @TODO: convert renderer to use template file
 */
namespace Bronto\Common\Block\Config\Form\Fieldset;

class About extends \Magento\Backend\Block\System\Config\Form\Fieldset
{
    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        array $data = array()
    ) {
        $this->_authSession = $authSession;
        parent::__construct($context, $data);
    }

    /**
     * Render fieldset html
     *
     * @param \Magento\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Data\Form\Element\AbstractElement $element)
    {
        $this->setElement($element);

        $html = $this->_getHeaderHtml($element);

        // Start Building proper HTML
        /*
        #bronto_about_container {
            background-color: #f4f9f5;
            border: 1px solid #D6D6D6;
            margin-bottom: 10px;
            padding: 10px 30px 10px 30px;
            color: #000;
        }
        #bronto_resource_center_link {
            font-weight: bold;
            font-size: 14px;
        }
        */
        $html .= '<div id="bronto_about_container">
    <div style="overflow:hidden;">
        <div style="float:left;width:50%">
            <a href="http://app.bronto.com" target="_blank" title="Home Dashboard |&nbsp;Bronto Marketing Platform"><img src="[IMAGE URL]"></a>
            <br />
            <strong style="font-size: 16px;">Bronto Extension for Magento 2 (v0.0.1)</strong>
            <br />For documentation and release notes, visit the Bronto
            <br /><a href="http://a.bron.to/magento" id="bronto_resource_center_link" target="_blank" title="Magento Resource Center | Bronto Developers">Magento Resource Center</a>
            <br />
            <span style="color: #666;font-size: 11px;">&copy; 2011-2013&nbsp;<a href="http://bronto.com" target="_blank" title="Marketing Automation for Commerce |&nbsp;Bronto Software" style="color: #666;">Bronto Software, Inc.</a></span>
        </div>
        <div style="float:right;max-width: 400px;font-size:11px;width: 50%;">
            <div style="margin:auto auto;padding-top: 5px;">
                <p style="color:#666;margin-bottom: 10px;">
                <strong style="font-size:14px;">Need help with your implementation?</strong>
                <br />
                The Bronto Professional Services team can help with your implementation. We have the expertise and resources to help you
                get up-and-running quickly. Ask your Account Manager for more
                details.
                </p>
                <p style="color:#666;margin-bottom:10px;">
                <strong style="font-size:14px;">Not a Bronto customer?</strong>
                <br />
                To take advantage of the Bronto Extension for Magento, you
                need a customer subscription to the Bronto Marketing Platform.
                Please click <a target="_blank" href="http://a.bron.to/magento_customer">here</a> to engage with us.
                </p>
            </div>
        </div>
    </div>
</div>';

        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    /**
     * Return header html for fieldset
     *
     * @param \Magento\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getHeaderHtml($element)
    {
        $html = '<div class="' . $this->_getFrontendClass($element) . ' ">';

        $html .= '<fieldset class="' . $this->_getFieldsetCss() . '" id="' . $element->getHtmlId() . '">';
        $html .= '<legend>' . $element->getLegend() . '</legend>';

        $html .= $this->_getHeaderCommentHtml($element);

        return $html;
    }

    /**
     * Return footer html for fieldset
     * Add extra tooltip comments to elements
     *
     * @param \Magento\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getFooterHtml($element)
    {
        $html = '</fieldset>' . $this->_getExtraJs($element);

        $html .= '</div>';

        return $html;
    }
}