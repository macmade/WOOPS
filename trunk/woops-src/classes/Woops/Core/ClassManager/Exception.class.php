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
namespace Woops\Core\ClassManager;

/**
 * Exception class for the Woops\Core\ClassManager class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Class.Manager
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
    const EXCEPTION_NOT_SINGLETON      = 0x01;
    const EXCEPTION_NOT_MULTISINGLETON = 0x02;
}