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
namespace Woops\Core\Module\Manager;

/**
 * Exception class for the Woops\Core\Module\Manager class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Module.Manager
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
    const EXCEPTION_NO_DIRECTORY           = 0x01;
    const EXCEPTION_NO_MODULE              = 0x02;
    const EXCEPTION_MODULE_NOT_LOADED      = 0x03;
    const EXCEPTION_BLOCK_TYPE_EXISTS      = 0x04;
    const EXCEPTION_NO_BLOCK_ABSTRACT      = 0x05;
    const EXCEPTION_INVALID_BLOCK_ABSTRACT = 0x06;
    const EXCEPTION_NO_BLOCK_TYPE          = 0x07;
    const EXCEPTION_BLOCK_EXISTS           = 0x08;
    const EXCEPTION_NO_BLOCK_CLASS         = 0x09;
    const EXCEPTION_INVALID_BLOCK_CLASS    = 0x10;
    const EXCEPTION_NO_BLOCK               = 0x11;
    const EXCEPTION_BAD_XML                = 0x12;
}
