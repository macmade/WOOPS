<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
# All rights reserved                                                          #
################################################################################

# $Id$

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Http\Client;

/**
 * Exception class for the Woops\Http\Client class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Http.Client
 */
final class Exception extends \Woops\Core\Exception\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_CONNECTED                = 0x01;
    const EXCEPTION_NOT_CONNECTED            = 0x02;
    const EXCEPTION_INVALID_REQUEST_METHOD   = 0x03;
    const EXCEPTION_INVALID_AUTH_TYPE        = 0x04;
    const EXCEPTION_INVALID_PROTOCOL_VERSION = 0x05;
    const EXCEPTION_NO_FSOCKOPEN             = 0x06;
    const EXCEPTION_NO_FILE                  = 0x07;
    const EXCEPTION_FILE_NOT_READABLE        = 0x08;
}
