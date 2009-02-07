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
 * WOOPS error handler class
 * 
 * Error levels that cannot be handled:
 *      - E_ERROR
 *      - E_PARSE
 *      - E_CORE_ERROR         - Since PHP 4.0.0
 *      - E_CORE_WARNING       - Since PHP 4.0.0
 *      - E_COMPILE_ERROR      - Since PHP 4.0.0
 *      - E_COMPILE_WARNING    - Since PHP 4.0.0
 * 
 * Error levels that can be handled:
 *      - E_WARNING
 *      - E_NOTICE             - Since PHP 4.0.0
 *      - E_STRICT             - Since PHP 4.0.0
 *      - E_USER_ERROR         - Since PHP 4.0.0
 *      - E_USER_WARNING       - Since PHP 4.0.0
 *      - E_USER_NOTICE        - Since PHP 4.0.0
 *      - E_RECOVERABLE_ERROR  - Since PHP 5.2.0
 *      - E_DEPRECATED         - Since PHP 5.3.0
 *      - E_USER_DEPRECATED    - Since PHP 5.3.0
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Error
 */
final class Woops_Core_Error_Handler
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Class constructor
     * 
     * The class constructor is private as all methods from this class are
     * static.
     * 
     * @return  NULL
     */
    private function __construct()
    {}
    
    /**
     * 
     */
    public static function handleError( $code , $message, $file = '', $line = 0, array $context = array() )
    {
        throw new Woops_Core_Php_Error_Exception(
            Woops_Core_Exception_Base::getExceptionString( 'Woops_Core_Php_Error_Exception', $code ) . ': ' . $message,
            $code
        );
    }
}
