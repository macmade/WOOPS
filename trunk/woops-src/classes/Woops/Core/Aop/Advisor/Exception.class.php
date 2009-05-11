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
namespace Woops\Core\Aop\Advisor;

/**
 * Exception class for the Woops\Core\Aop\Advisor class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Aop.Advisor
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
    const EXCEPTION_NO_JOINPOINT              = 0x01;
    const EXCEPTION_NO_JOINPOINT_METHOD       = 0x02;
    const EXCEPTION_INVALID_ADVICE_TYPE       = 0x03;
    const EXCEPTION_JOINPOINT_EXISTS          = 0x04;
    const EXCEPTION_ADVICE_TYPE_NOT_PERMITTED = 0x05;
    const EXCEPTION_NO_CLASS                  = 0x06;
}
