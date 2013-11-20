<?php

/**
 * Bronto Formatting Options Source
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 */
namespace Bronto\Common\Model\Config\Source;

class ImageType
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'image', 'label' => __('Image')),
            array('value' => 'small_image', 'label' => __('Small Image')),
            array('value' => 'thumbnail', 'label' => __('Thumbnail')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'image' => __('Image'),
            'small_image' => __('Small Image'),
            'thumbnail' => __('Thumbnail')
        );
    }
}