<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Exception class for the Woops_Http_Client class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Http.Client
 */
final class Woops_Http_Client_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_CONNECTED                = 0x01;
    const EXCEPTION_NOT_CONNECTED            = 0x02;
    const EXCEPTION_INVALID_REQUEST_METHOD   = 0x03;
    const EXCEPTION_INVALID_AUTH_TYPE        = 0x04;
    const EXCEPTION_INVALID_PROTOCOL_VERSION = 0x05;
    const EXCEPTION_NO_FSOCKOPEN             = 0x06;
}
