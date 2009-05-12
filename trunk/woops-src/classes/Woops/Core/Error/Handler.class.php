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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Core\Error;

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
final class Handler extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * 
     */
    private $_disabledErrors  = 0;
    
    /**
     * 
     */
    public static function handleError( $code , $message, $file = '', $line = 0, array $context = array() )
    {
        self::getInstance()->_handleError( $code , $message, $file, $line, $context );
    }
    
    /**
     * 
     */
    private function _handleError( $code , $message, $file = '', $line = 0, array $context = array() )
    {
        if( !( $code & $this->_disabledErrors ) ) {
            
            throw new \Woops\Core\Php\Error\Exception(
                \Woops\Core\Exception\Base::getExceptionString( 'Woops\Core\Php\Error\Exception', $code ) . ': ' . $message,
                $code
            );
        }
    }
    
    /**
     * 
     */
    public function disableErrorReporting( $type )
    {
        $this->_disabledErrors |= $type;
    }
    
    /**
     * 
     */
    public function resetErrorReporting()
    {
        $this->_disabledErrors = 0;
    }
}
