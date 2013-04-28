<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Exception class for the Woops_Http_Response class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Http.Response
 */
final class Woops_Http_Response_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_INVALID_CODE              = 0x01;
    const EXCEPTION_INVALID_HTTP_STATUS       = 0x02;
    const EXCEPTION_INVALID_CHUNKED_CONTENT   = 0x03;
    const EXCEPTION_NO_GZUNCOMPRESS           = 0x04;
    const EXCEPTION_NO_GZINFLATE              = 0x05;
    const EXCEPTION_INVALID_RESOURCE          = 0x06;
    const EXCEPTION_NO_DATA                   = 0x07;
    const EXCEPTION_INVALID_TRANSFER_ENCODING = 0x08;
}
