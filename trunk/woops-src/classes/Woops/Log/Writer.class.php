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
namespace Woops\Log;

/**
 * Log writer
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Log
 */
class Writer extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
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
     * The reigistered loggers
     */
    protected $_loggers  = array();
    
    /**
     * The name of the log types
     */
    protected $_logTypes = array(
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
    protected function __construct()
    {}
    
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
            throw new Writer\Exception(
                'Invalid log type ' . $message . ' for message \'' . $message . '\'',
                Writer\Exception::EXCEPTION_INVALID_LOG_TYPE
            );
        }
        
        // Dispatch the event to the listeners
        $this->dispatchEvent( Writer\Event::EVENT_LOG );
        
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
     * throws   Woops\Log\Writer\Exception  If the log writer class if already registered
     * throws   Woops\Log\Writer\Exception  If the log writer class does not exists
     * throws   Woops\Log\Writer\Exception  If the log writer class does not implements the Woops\Log\Writer\ObjectInterface interface
     */
    public function registerLogWriter( $class, $types = 0x00FF )
    {
        // Checks for a leading backslash
        if( substr( $class, 0, 1 ) !== '\\' ) {
            
            // Adds the leading backslash
            $class = '\\' . $class;
        }
        
        // Type correction
        $types = $types & self::LOG_TYPE_ALL;
        
        // Checks if the log writer is already registered
        if( isset( $this->_loggers[ $class ] ) ) {
            
            // Class is already registered
            throw new Writer\Exception(
                'The log writer \'' . $class . '\' is already registered',
                Writer\Exception::EXCEPTION_WRITER_EXISTS
            );
        }
        
        // Checks if the class exists
        if( !class_exists( $class ) ) {
            
            // The class does not exists
            throw new Writer\Exception(
                'Cannot register unexisting class \'' . $class . '\' as a log writer',
                Writer\Exception::EXCEPTION_NO_WRITER
            );
        }
        
        // Gets the interfaces of the log writer class
        $interfaces = class_implements( $class );
        
        // Checks if the log writer class implements the log writer interface
        if( !isset( $interfaces[ 'Woops\Log\Writer\ObjectInterface' ] ) ) {
            
            // Error - The log writer class must extends the log writer interface
            throw new Writer\Exception(
                'Cannot register class \'' . $class . '\' as a log writer, since it does not implements the \'Woops\Log\Writer\ObjectInterface\' interface',
                Writer\Exception::EXCEPTION_INVALID_WRITER_CLASS
            );
        }
        
        // Dispatch the event to the listeners
        $this->dispatchEvent( Writer\Event::EVENT_LOG_WRITER_REGISTER );
        
        // Registers the log writer class
        $this->_loggers[ $class ] = array(
            \Woops\Core\ClassManager::getInstance()->getSingleton( $class ),
            $types
        );
    }
}
