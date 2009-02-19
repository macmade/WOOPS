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
 * Log writer
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Log
 */
final class Woops_Log_Writer implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The types of log that can be used
     */
    const LOG_TYPE_INFO     = 0x0001;
    const LOG_TYPE_NOTICE   = 0x0002;
    const LOG_TYPE_WARNING  = 0x0004;
    const LOG_TYPE_ERROR    = 0x0008;
    const LOG_TYPE_CRITICAL = 0x0010;
    const LOG_TYPE_BLOCKER  = 0x0020;
    const LOG_TYPE_SECURITY = 0x0040;
    const LOG_TYPE_DEBUG    = 0x0080;
    
    /**
     * Groups of log types
     */ 
    const LOG_TYPE_ERRORS   = 0x007E;
    const LOG_TYPE_ALL      = 0x00FF;
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The reigistered loggers
     */
    private $_loggers         = array();
    
    /**
     * The name of the log types
     */
    private $_logTypes        = array(
        0x0001 => 'LOG_TYPE_INFO',
        0x0001 => 'LOG_TYPE_NOTICE',
        0x0001 => 'LOG_TYPE_WARNING',
        0x0001 => 'LOG_TYPE_ERROR',
        0x0001 => 'LOG_TYPE_CRITICAL',
        0x0001 => 'LOG_TYPE_BLOCKER',
        0x0001 => 'LOG_TYPE_SECURITY',
        0x0001 => 'LOG_TYPE_DEBUG'
    );
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  void
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
     * @return  Woops_Log_Writer    The unique instance of the class
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
    public function log( $message, $type = 0x01 )
    {
        $type = $type & self::LOG_TYPE_ALL;
        $time = time();
        
        if( !isset( $this->_logTypes[ $type ] ) ) {
            
            throw new Woops_Log_Writer_Exception(
                'Invalid log type ' . $message . ' for message \'' . $message . '\'',
                Woops_Log_Writer_Exception::EXCEPTION_INVALID_LOG_TYPE
            );
        }
        
        foreach( $this->_loggers as $key => $value ) {
            
            $logger = $value[ 0 ];
            $types  = $value[ 1 ];
            
            if( $type & $types ) {
                
                $logger->write(
                    $message,
                    $time,
                    $type,
                    $this->_logTypes[ $type ]
                );
            }
        }
    }
    
    /**
     * 
     */
    public function registerLogWriterClass( $class, $types = 0x00FF )
    {
        $types = $types & self::LOG_TYPE_ALL;
        
        if( isset( $this->_loggers[ $class ] ) ) {
            
            throw new Woops_Log_Writer_Exception(
                'The log writer \'' . $class . '\' is already registered',
                Woops_Log_Writer_Exception::EXCEPTION_WRITER_EXISTS
            );
        }
        
        if( !class_exists( $class ) ) {
            
            throw new Woops_Log_Writer_Exception(
                'Cannot register unexisting class \'' . $class . '\' as a log writer',
                Woops_Log_Writer_Exception::EXCEPTION_NO_WRITER
            );
        }
        
        $interfaces = class_implements( $class );
        
        if( !isset( $interfaces[ 'Woops_Log_Writer_Interface' ] ) ) {
            
            throw new Woops_Log_Writer_Exception(
                'Cannot register class \'' . $class . '\' as a log writer, since it does not implements the \'Woops_Log_Writer_Interface\' interface',
                Woops_Log_Writer_Exception::EXCEPTION_INVALID_WRITER_CLASS
            );
        }
        
        $ref                      = Woops_Core_Reflection_Method::getInstance( $class, 'getInstance' );
        $this->_loggers[ $class ] = array(
            $ref->invoke( array() ),
            $types
        );
    }
}
