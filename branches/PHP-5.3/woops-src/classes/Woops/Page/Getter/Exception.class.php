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
namespace Woops\Page\Getter;

/**
 * Exception class for the Woops\Page\Getter class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Page.Getter
 */
final class Exception extends \Woops\Core\Exception\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_DEFAULT_LANG  = 0x01;
    const EXCEPTION_NO_HOMEPAGE      = 0x02;
    const EXCEPTION_NO_PAGE          = 0x03;
    const EXCEPTION_NO_TEMPLATE      = 0x04;
}
