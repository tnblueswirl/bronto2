<?php

/**
 * Bronto Common Api Token Field Frontend Model
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 */
namespace Bronto\Common\Block\Config\Form\Field;

class Apitoken extends \Magento\Backend\Block\System\Config\Form\Field
{
    /**
     * @param \Magento\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(\Magento\Data\Form\Element\AbstractElement $element)
    {
        $html = '';
        if ($element->getBeforeElementHtml()) {
            $html .= '<label class="addbefore" for="' . $element->getHtmlId() . '">' . $element->getBeforeElementHtml() . '</label>';
        }
        $html .= '<input minlength="36" maxlength="36" remote="http://mage2.devel/index.php/admin/admin/apitoken/verifyToken/" id="' . $element->getHtmlId() . '" name="' . $element->getName() . '" '
            . ' data-ui-id="form-element-' . $element->getName() . '"'
            . ' value="' . $element->getEscapedValue() . '" ' . $element->serialize($element->getHtmlAttributes()) . '/>';
        if ($element->getAfterElementHtml()) {
            $html.= '<label class="addafter" for="' . $element->getHtmlId() . '">' . $element->getAfterElementHtml() . '</label>';
        }

        return $html;
    }
}
