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
 * Exception class for the Woops_Lexical_Analyser class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Lexical.Analyser
 */
final class Woops_Lexical_Analyser_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Error codes for the exceptions
     */
    const EXCEPTION_NO_TOKENS            = 0x01;
    const EXCEPTION_NO_TOKEN_CONSTANT    = 0x02;
    const EXCEPTION_DUPLICATE_TOKEN_CODE = 0x03;
}
