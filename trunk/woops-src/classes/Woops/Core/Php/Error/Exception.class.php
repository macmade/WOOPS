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
 * WOOPS PHP error exception class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Php.Error
 */
class Woops_Core_Php_Error_Exception extends Woops_Core_Exception_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    const E_WARNING           = E_WARNING;
    const E_NOTICE            = E_NOTICE;
    const E_STRICT            = E_STRICT;
    const E_USER_ERROR        = E_USER_ERROR;
    const E_USER_WARNING      = E_USER_WARNING;
    const E_USER_NOTICE       = E_USER_NOTICE;
    const E_RECOVERABLE_ERROR = E_RECOVERABLE_ERROR;
    const E_DEPRECATED        = 0x2000; // The E_DEPRECATED constant is only available since PHP 5.3.0
    const E_USER_DEPRECATED   = 0x4000; // The E_USER_DEPRECATED constant is only available since PHP 5.3.0
}
