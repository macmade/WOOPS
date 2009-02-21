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
 * Exception class for the Woops_Http_Response class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Http.Response
 */
final class Woops_Http_Response_Response extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_INVALID_CODE            = 0x01;
    const EXCEPTION_INVALID_HTTP_STATUS     = 0x02;
    const EXCEPTION_INVALID_CHUNKED_CONTENT = 0x03;
    const EXCEPTION_NO_GZUNCOMPRESS         = 0x04;
    const EXCEPTION_NO_GZINFLATE            = 0x05;
}
