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
 * Exception class for the Woops_Mod_Pdo_Database_Engine class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mod.Pdo.Database.Engine
 */
final class Woops_Mod_Pdo_Database_Engine_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_PDO            = 0x01;
    const EXCEPTION_NO_PDO_DRIVER     = 0x02;
    const EXCEPTION_NO_CONNECTION     = 0x03;
    const EXCEPTION_BAD_METHOD        = 0x04;
    const EXCEPTION_INVALID_STATEMENT = 0x05;
}
