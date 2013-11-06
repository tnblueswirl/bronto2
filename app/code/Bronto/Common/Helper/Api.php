<?php

/**
 * Bronto Common Api Helper
 *
 * @category    Bronto2
 * @package     Bronto_Common
 * @author      Adam Daniels <adam.daniels@atlanticbt.com>
 */
namespace Bronto\Common\Helper;

use Magento\Core\Model\Config\Cache\Exception;

class Api extends Data
{
    /**
     * Performs validation on provided $token, including logging into the API.
     *
     * @param string $token
     *
     * @return bool
     */
    public function validateToken($token)
    {
        // Ensure Token Consists of Expected Characters
        $token = $token = preg_replace('/[^A-Z0-9\-]/', '', $token);

        // TODO: Perform Login to API
        // TODO: Check Token Permissions

        return true;
    }
}