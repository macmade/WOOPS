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
namespace Woops\Core\Php\Error;

/**
 * WOOPS PHP error exception class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Php.Error
 */
class Exception extends \Woops\Core\Exception\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The PHP error types
     */
    const E_WARNING           = E_WARNING;
    const E_NOTICE            = E_NOTICE;
    const E_STRICT            = E_STRICT;
    const E_USER_ERROR        = E_USER_ERROR;
    const E_USER_WARNING      = E_USER_WARNING;
    const E_USER_NOTICE       = E_USER_NOTICE;
    const E_RECOVERABLE_ERROR = E_RECOVERABLE_ERROR;
    const E_DEPRECATED        = E_DEPRECATED;
    const E_USER_DEPRECATED   = E_USER_DEPRECATED;
}
