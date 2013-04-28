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
 * Exception class for the Woops_Binary_Stream class
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Binary.Stream
 */
final class Woops_Binary_Stream_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_END_OF_STREAM     = 0x01;
    const EXCEPTION_INVALID_SEEK_TYPE = 0x02;
    const EXCEPTION_BAD_ISO_639_CODE  = 0x03;
}
