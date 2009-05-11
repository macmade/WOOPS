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
 * Exception class for the Woops_Log_Writer class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Log.Writer
 */
final class Woops_Log_Writer_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_INVALID_LOG_TYPE     = 0x01;
    const EXCEPTION_WRITER_EXISTS        = 0x02;
    const EXCEPTION_NO_WRITER            = 0x03;
    const EXCEPTION_INVALID_WRITER_CLASS = 0x04;
}
