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

# $Id: Exception.class.php 559 2009-03-04 17:18:24Z macmade $

/**
 * Exception class for the Woops_Zip_Central_Directory class
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Zip.Central.Directory
 */
final class Woops_Zip_Central_Directory_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_BAD_FILE_HEADER_SIGNATURE = 0x01;
}
