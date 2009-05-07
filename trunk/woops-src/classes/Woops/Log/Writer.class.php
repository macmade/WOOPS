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
 * Log writer
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Log
 */
final class Woops_Log_Writer extends Woops_Core_Object implements Woops_Core_Singleton_Interface
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
        0x0002 => 'LOG_TYPE_NOTICE',
        0x0004 => 'LOG_TYPE_WARNING',
        0x0008 => 'LOG_TYPE_ERROR',
        0x0010 => 'LOG_TYPE_CRITICAL',
        0x0020 => 'LOG_TYPE_BLOCKER',
        0x0040 => 'LOG_TYPE_SECURITY',
        0x0080 => 'LOG_TYPE_DEBUG'
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
     * Logs a message
     * 
     * @param   string  The message to log
     * @param   int     The log type (some LOG_TYPE_XXX constants)
     * @return  void
     */
    public function log( $message, $type = 0x01 )
    {
        // Type correction
        $type = $type & self::LOG_TYPE_ALL;
        
        // Gets the current time
        $time = time();
        
        // Checks the log type
        if( !isset( $this->_logTypes[ $type ] ) ) {
            
            // Invalid log type
            throw new Woops_Log_Writer_Exception(
                'Invalid log type ' . $message . ' for message \'' . $message . '\'',
                Woops_Log_Writer_Exception::EXCEPTION_INVALID_LOG_TYPE
            );
        }
        
        // Process all the registered loggers
        foreach( $this->_loggers as $key => $value ) {
            
            // Instance of the logger class
            $logger = $value[ 0 ];
            
            // Supported types
            $types  = $value[ 1 ];
            
            // Checks if the logger supports the log type
            if( $type & $types ) {
                
                // Writes the log
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
     * Registers a log writer class
     * 
     * @param   string                      The name of the log writer class
     * @param   int                         The log types supported by the log writer class (some LOG_TYPE_XXX constants)
     * @return  void
     * throws   Woops_Log_Writer_Exception  If the log writer class if already registered
     * throws   Woops_Log_Writer_Exception  If the log writer class does not exists
     * throws   Woops_Log_Writer_Exception  If the log writer class does not implements the Woops_Log_Writer_Interface interface
     */
    public function registerLogWriter( $class, $types = 0x00FF )
    {
        // Type correction
        $types = $types & self::LOG_TYPE_ALL;
        
        // Checks if the log writer is already registered
        if( isset( $this->_loggers[ $class ] ) ) {
            
            // Class is already registered
            throw new Woops_Log_Writer_Exception(
                'The log writer \'' . $class . '\' is already registered',
                Woops_Log_Writer_Exception::EXCEPTION_WRITER_EXISTS
            );
        }
        
        // Checks if the class exists
        if( !class_exists( $class ) ) {
            
            // The class does not exists
            throw new Woops_Log_Writer_Exception(
                'Cannot register unexisting class \'' . $class . '\' as a log writer',
                Woops_Log_Writer_Exception::EXCEPTION_NO_WRITER
            );
        }
        
        // Gets the interfaces of the log writer class
        $interfaces = class_implements( $class );
        
        // Checks if the log writer class implements the log writer interface
        if( !isset( $interfaces[ 'Woops_Log_Writer_Interface' ] ) ) {
            
            // Error - The log writer class must extends the log writer interface
            throw new Woops_Log_Writer_Exception(
                'Cannot register class \'' . $class . '\' as a log writer, since it does not implements the \'Woops_Log_Writer_Interface\' interface',
                Woops_Log_Writer_Exception::EXCEPTION_INVALID_WRITER_CLASS
            );
        }
        
        // Registers the log writer class
        $this->_loggers[ $class ] = array(
            Woops_Core_Class_Manager::getInstance()->getSingleton( $class ),
            $types
        );
    }
}
