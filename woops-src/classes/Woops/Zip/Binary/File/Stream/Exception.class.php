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
 * Exception class for the Woops_Zip_Binary_File_Stream class
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Zip.Binary.File.Stream
 */
final class Woops_Zip_Binary_File_Stream_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_FILE           = 0x01;
    const EXCEPTION_FILE_NOT_READABLE = 0x02;
}
