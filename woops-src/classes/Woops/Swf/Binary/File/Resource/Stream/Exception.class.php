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
 * Exception class for the Woops_Swf_Binary_File_Resource_Stream class
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Swf.Binary.File.Resource.Stream
 */
final class Woops_Swf_Binary_File_Resource_Stream_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_RESOURCE = 0x01;
}
