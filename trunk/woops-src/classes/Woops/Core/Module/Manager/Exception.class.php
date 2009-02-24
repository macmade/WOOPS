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

/**
 * Exception class for the Woops_Core_Module_Manager class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Module.Manager
 */
final class Woops_Core_Module_Manager_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
}
