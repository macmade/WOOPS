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

# $Id: Angle.class.php 434 2009-02-24 15:19:13Z macmade $

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Test;

/**
 * Unit testing suite
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Test
 */
class Suite extends \Woops\Core\Object implements \Iterator
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The test units
     */
    protected $_units       = array();
    
    /**
     * The log file resource
     */
    protected $_log         = NULL;
    
    /**
     * The SPL iterator position
     */
    protected $_iteratorPos = 0;
    
    /**
     * Class constructor
     * 
     * @param   string  The name of the log file
     * @return  void
     */
    public function __construct( $logFile = '' )
    {
        // Checks if we have to log the results
        if( $logFile ) {
            
            // Checks if the log file exists
            if( !file_exists( $logFile ) ) {
                
                // Error - Log file does not exists
                throw new Suite\Exception(
                    'The log file does not exist (path: ' . $logFile . ')',
                    Suite\Exception::EXCEPTION_NO_LOG_FILE
                );
            }
            
            // Checks if the log file is writeable
            if( !is_writable( $logFile ) ) {
                
                // Error - Log file is not writeable
                throw new Suite\Exception(
                    'The log file is not writeable (path: ' . $logFile . ')',
                    Suite\Exception::EXCEPTION_LOG_FILE_NOT_WRITEABLE
                );
            }
            
            // Opens a file handle for the log file
            $this->_log = fopen( $logFile, 'w' );
            
            // Checks the file handle
            if( !$this->_log ) {
                
                // Error - Invalid file handle
                throw new Suite\Exception(
                    'Impossible to create a file handle for the log file (path: ' . $logFile . ')',
                    Suite\Exception::EXCEPTION_BAD_LOG_FILE_HANDLE
                );
            }
        }
    }
    
    /**
     * Gets the current test unit object (SPL Iterator method)
     * 
     * @return  Woops\Test\Unit   The current test unit object
     */
    public function current()
    {
        return $this->_units[ $this->_iteratorPos ];
    }
    
    /**
     * Moves to the next test unit object (SPL Iterator method)
     * 
     * @return  void
     */
    public function next()
    {
        $this->_iteratorPos++;
    }
    
    /**
     * Gets the index of the current test unit object (SPL Iterator method)
     * 
     * @return  int     The index of the current test unit
     */
    public function key()
    {
        return $this->_iteratorPos;
    }
    
    /**
     * Checks if there is a next test unit object (SPL Iterator method)
     * 
     * @return  boolean True if there is a next test unit, otherwise false
     */
    public function valid()
    {
        return $this->_iteratorPos < count( $this->_units );
    }
    
    /**
     * Rewinds the SPL Iterator pointer (SPL Iterator method)
     * 
     * @return  void
     */
    public function rewind()
    {
        $this->_iteratorPos = 0;
    }
    
    /**
     * Adds a test unit to the test suite
     * 
     * @param   Woops\Test\Unit The test unit to add
     * @return  void
     */
    public function addUnit( Unit $unit )
    {
        $this->_units[] = $unit;
    }
    
    /**
     * Runs all the registered test units
     * 
     * @return  void
     */
    public function run()
    {
        // Process each test unit
        foreach( $this->_units as $index => $test ) {
            
            // Start timer
            $start = microtime( true );
            
            // No errors, as we are running tests
            try {
                
                // Runs the current test unit
                $test->run();
                
                // End timer
                $end = microtime( true );
                
            } catch( Exception $e ) {
                
                // End timer
                $end = microtime( true );
            }
        }
    }
}
