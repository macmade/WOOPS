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
 * Exception class for the Woops_Database_Layer class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Database.Layer
 */
final class Woops_Database_Layer_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_ENGINE            = 0x01;
    const EXCEPTION_ENGINE_EXISTS        = 0x02;
    const EXCEPTION_NO_ENGINE_CLASS      = 0x03;
    const EXCEPTION_INVALID_ENGINE_CLASS = 0x04;
    const EXCEPTION_BAD_CONFIGURATION    = 0x05;
    const EXCEPTION_DRIVER_NOT_SUPPORTED = 0x06;
}
