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
 * Exception class for the Woops_Page_Engine class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Page.Engine
 */
final class Woops_Page_Engine_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_ENGINE_CLASS       = 0x01;
    const EXCEPTION_INVALID_ENGINE_CLASS  = 0x02;
    const EXCEPTION_ENGINE_NOT_REGISTERED = 0x03;
    const EXCEPTION_ENGINE_NOT_VALID      = 0x04;
}
