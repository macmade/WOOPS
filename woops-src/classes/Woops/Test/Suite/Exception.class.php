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

# $Id: Exception.class.php 540 2009-03-03 10:24:44Z macmade $

/**
 * Exception class for the Woops_Test_Suite class
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Test.Suite
 */
final class Woops_Test_Suite_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_LOG_FILE            = 0x01;
    const EXCEPTION_LOG_FILE_NOT_WRITEABLE = 0x02;
    const EXCEPTION_BAD_LOG_FILE_HANDLE    = 0x03;
}
