<?php

namespace Bronto\Common\Block\System\Config\Form\Field;

class Apitoken extends \Magento\Backend\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Data\Form\Element\AbstractElement $element)
    {

        $element->addClass('token-valid');

        $html = '';
        if ($element->getBeforeElementHtml()) {
            $html .= '<label class="addbefore" for="' . $element->getHtmlId() . '">' . $element->getBeforeElementHtml() . '</label>';
        }
        $html .= '<input ref="waffle" minlength="36" maxlength="36" remote="http://mage2.devel/index.php/admin/admin/apitoken/verifyToken/" id="' . $element->getHtmlId() . '" name="' . $element->getName() . '" '
            . ' data-ui-id="form-element-' . $element->getName() . '"'
            . ' value="' . $element->getEscapedValue() . '" ' . $element->serialize($element->getHtmlAttributes()) . '/>';
        if ($element->getAfterElementHtml()) {
            $html.= '<label class="addafter" for="' . $element->getHtmlId() . '">' . $element->getAfterElementHtml() . '</label>';
        }

        return $html;
    }


}
