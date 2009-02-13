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
 * Exception class for the Woops_Core_Aop_Advisor class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Aop.Advisor
 */
final class Woops_Core_Aop_Advisor_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_JOINPOINT              = 0x01;
    const EXCEPTION_NO_JOINPOINT_METHOD       = 0x02;
    const EXCEPTION_INVALID_ADVICE_CALLBACK   = 0x03;
    const EXCEPTION_INVALID_ADVICE_TYPE       = 0x04;
    const EXCEPTION_JOINPOINT_EXISTS          = 0x05;
    const EXCEPTION_ADVICE_TYPE_NOT_PERMITTED = 0x06;
    const EXCEPTION_NO_CLASS                  = 0x07;
}
