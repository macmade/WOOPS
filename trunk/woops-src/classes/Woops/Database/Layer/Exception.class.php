<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Exception class for the Woops_Database_Layer class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Config
 */
final class Woops_Database_Layer_Exception extends Woops_Core_Exception_Base
{
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_CONFIG_NOT_SET     = 0x01;
    const EXCEPTION_CONFIG_ALREADY_SET = 0x02;
    const EXCEPTION_NO_PDO             = 0x03;
    const EXCEPTION_NO_PDO_DRIVER      = 0x04;
    const EXCEPTION_NO_CONNECTION      = 0x05;
    const EXCEPTION_BAD_METHOD         = 0x06;
    const EXCEPTION_DBAL_INVALID_CONF  = 0x07;
}
