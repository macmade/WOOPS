<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Exception class for the Woops_Xml_Parser class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Xml.Parser
 */
final class Woops_Xml_Parser_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_FILE           = 0x01;
    const EXCEPTION_FILE_NOT_READABLE = 0x02;
    const EXCEPTION_INVALID_CHARSET   = 0x03;
    const EXCEPTION_XML_PARSER_ERROR  = 0x04;
    const EXCEPTION_PI_EXISTS         = 0x05;
    const EXCEPTION_NO_PI_CLASS       = 0x06;
    const EXCEPTION_INVALID_PI_CLASS  = 0x07;
}
