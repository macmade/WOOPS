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
final class Woops_Core_Error_Handler extends Woops_Core_Object implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * 
     */
    private $_disabledErrors  = 0;
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return void
     */
    private function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Core_Error_Handler    The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
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
            
            throw new Woops_Core_Php_Error_Exception(
                Woops_Core_Exception_Base::getExceptionString( 'Woops_Core_Php_Error_Exception', $code ) . ': ' . $message,
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
